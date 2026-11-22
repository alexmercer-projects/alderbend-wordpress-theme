// Opens a scratch page that contains every Alderbend pattern in the real block editor and reports blocks that fail validation.
// Usage: node tools/editor-check.mjs <post-id> [site-url]  (logs in as a throwaway local admin; Node 22+)
import { spawn } from 'node:child_process';
const [postId,site='http://localhost:8080']=process.argv.slice(2);
const port=9700+Math.floor(Math.random()*200);
const chrome=spawn('google-chrome',['--headless=new','--no-sandbox','--disable-gpu',`--remote-debugging-port=${port}`,`--user-data-dir=/tmp/ab-ed-${port}`,'about:blank'],{stdio:'ignore'});
const sleep=ms=>new Promise(r=>setTimeout(r,ms));
let ws;for(let i=0;i<60&&!ws;i++){try{const t=await(await fetch(`http://127.0.0.1:${port}/json`)).json();const p=t.find(x=>x.type==='page');if(p)ws=p.webSocketDebuggerUrl}catch{}await sleep(150)}
const sock=new WebSocket(ws);await new Promise(r=>sock.onopen=r);
let id=0;const pend=new Map();const logs=[];
sock.onmessage=m=>{const d=JSON.parse(m.data);if(d.id&&pend.has(d.id)){pend.get(d.id)(d.result||d.error);pend.delete(d.id)}
 if(d.method==='Runtime.consoleAPICalled'&&['error','warning'].includes(d.params.type))logs.push(d.params.type+': '+d.params.args.map(a=>a.value||a.description||'').join(' ').slice(0,300));
 if(d.method==='Runtime.exceptionThrown')logs.push('exception: '+(d.params.exceptionDetails.exception?.description||d.params.exceptionDetails.text).slice(0,300));};
const send=(method,params={})=>new Promise(r=>{const i=++id;pend.set(i,r);sock.send(JSON.stringify({id:i,method,params}))});
const ev=async e=>(await send('Runtime.evaluate',{expression:e,returnByValue:true,awaitPromise:true})).result?.value;
await send('Page.enable');await send('Runtime.enable');
await send('Emulation.setDeviceMetricsOverride',{width:1440,height:900,deviceScaleFactor:1,mobile:false});
await send('Page.navigate',{url:site+'/wp-login.php'});await sleep(1500);
await ev(`document.getElementById('user_login').value='admin';document.getElementById('user_pass').value='pw';document.getElementById('wp-submit').click()`);await sleep(3000);
await send('Page.navigate',{url:`${site}/wp-admin/post.php?post=${postId}&action=edit`});await sleep(9000);
const r=await ev(`(async()=>{const blocks=wp.data.select('core/block-editor').getBlocks();const flat=[];const walk=b=>b.forEach(x=>{flat.push(x);walk(x.innerBlocks)});walk(blocks);return {total:flat.length,invalid:flat.filter(b=>!b.isValid).map(b=>b.name),names:[...new Set(flat.map(b=>b.name))].length}})()`);
console.log(JSON.stringify(r));console.log(logs.filter(l=>/validation|invalid|unexpected/i.test(l)).slice(0,10).join('\n')||'no validation messages');
console.log('other console errors:',logs.length);logs.slice(0,6).forEach(l=>console.log(' ',l));
sock.close();chrome.kill();
