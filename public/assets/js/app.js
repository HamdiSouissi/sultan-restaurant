'use strict';
const $=(s,c=document)=>c.querySelector(s),$$=(s,c=document)=>[...c.querySelectorAll(s)],calm=matchMedia('(prefers-reduced-motion:reduce)').matches;
gsap.registerPlugin(ScrollTrigger);AOS.init({duration:900,once:true,offset:80,disable:calm});
$$('.split').forEach(el=>{el.innerHTML=el.innerHTML.split(/<br\s*\/?>/).map(l=>`<span class="ln"><span>${l}</span></span>`).join('')});
addEventListener('load',()=>{
  if(!calm){
    $$('.chap .split,.quote .split').forEach(el=>gsap.from($$('.ln>span',el),{yPercent:115,duration:1.3,stagger:.15,ease:'power4.out',scrollTrigger:{trigger:el,start:'top 80%'}}));
    $$('[data-par]').forEach(el=>gsap.fromTo(el,{yPercent:-9},{yPercent:9,ease:'none',scrollTrigger:{trigger:el.parentElement,scrub:true,start:'top bottom',end:'bottom top'}}));
    $$('.street .arch-card').forEach((f,i)=>gsap.to(f,{y:i%2?40:-30,ease:'none',scrollTrigger:{trigger:'.street',scrub:true,start:'top bottom',end:'bottom top'}}));
    $$('.tile').forEach((t,i)=>gsap.fromTo($('img',t),{yPercent:-6},{yPercent:6,ease:'none',scrollTrigger:{trigger:t,scrub:true}}));gsap.to('.q-bg',{yPercent:20,ease:'none',scrollTrigger:{trigger:'.quote',scrub:true}});$$('.eb').filter(e=>!e.closest('.cap')).forEach(e=>gsap.from(e,{x:-20,opacity:0,duration:1,scrollTrigger:{trigger:e,start:'top 90%'}}));
  }
  $$('[data-n]').forEach(el=>ScrollTrigger.create({trigger:el,once:true,start:'top 92%',onEnter:()=>{const o={v:0};gsap.to(o,{v:+el.dataset.n,duration:calm?0:1.8,onUpdate:()=>el.textContent=Math.round(o.v)})}}));
});
const hd=$('#hd'),nav=$('#nav'),bg=$('#burger');addEventListener('scroll',()=>{hd.classList.toggle('sc',scrollY>40);$('#prog').style.width=(scrollY/(document.documentElement.scrollHeight-innerHeight)*100)+'%'},{passive:true});
function setMenu(open){if(!nav||!bg)return;nav.classList.toggle('open',open);bg.setAttribute('aria-expanded',open?'true':'false');document.body.classList.toggle('nav-open',open)}
if(bg&&nav)bg.onclick=()=>setMenu(!nav.classList.contains('open'));
$$('#nav a').forEach(a=>a.onclick=()=>setMenu(false));
addEventListener('keydown',e=>{if(e.key==='Escape')setMenu(false)});
addEventListener('resize',()=>{if(innerWidth>960)setMenu(false)});
const caps=$$('.cap');let capI=-1;
function showCap(i,first){const n=caps[i],o=capI>=0?caps[capI]:null;capI=i;
  const inn=()=>{caps.forEach(c=>c.style.visibility='hidden');n.style.visibility='visible';if(calm)return;
    gsap.fromTo($$('.ln>span',n),{yPercent:115},{yPercent:0,duration:1.1,stagger:.12,ease:'power4.out'});
    gsap.fromTo($$('.eb,.lead',n),{y:20,opacity:0},{y:0,opacity:1,duration:.9,stagger:.18,delay:.25});gsap.fromTo($$('.eb i',n),{scaleX:0},{scaleX:1,duration:1.2,ease:'power3.out'})};
  if(o&&o!==n&&!calm&&!first)gsap.to(o,{opacity:0,duration:.45,onComplete:()=>{gsap.set(o,{opacity:1});inn()}});else inn()}
const sl=$$('.hs-slide');let si=0,tmr;
function kb(n){if(calm)return;n.classList.remove('kb');void n.offsetWidth;n.classList.add('kb')}
function goSlide(i){const o=sl[si],n=sl[i];if(o===n)return;o.classList.remove('on');o.classList.add('prev');setTimeout(()=>o.classList.remove('prev','kb'),1700);si=i;kb(n);n.classList.add('on');showCap(i)}
function reveal(){
  gsap.to('#loader',{opacity:0,duration:.8,onComplete:()=>$('#loader').remove()});
  kb(sl[0]);
  if(calm){showCap(0,true);gsap.set('.cta,.coord,.vert,.badge',{opacity:1});return}
  gsap.from('.frame',{clipPath:'inset(50% 50% 50% 50%)',duration:1.6,ease:'power3.inOut',delay:1});
  setTimeout(()=>showCap(0,true),1500);
  gsap.to('.cta,.coord,.vert,.badge',{opacity:1,duration:1,stagger:.12,delay:2.1});
  tmr=setInterval(()=>goSlide((si+1)%sl.length),4000);
}
{let done=false;const go=()=>{if(!done){done=true;reveal()}},im=new Image();im.onload=im.onerror=()=>setTimeout(go,500);im.src='/assets/img/hero-slide-01.webp';setTimeout(go,4000)}
new Swiper('.sp',{slidesPerView:1.15,spaceBetween:22,loop:false,navigation:{nextEl:'.sp .swiper-button-next',prevEl:'.sp .swiper-button-prev'},pagination:{el:'.sp .swiper-pagination',clickable:true},breakpoints:{700:{slidesPerView:2.3},1050:{slidesPerView:3.2}}});
new Swiper('.ex',{centeredSlides:true,slidesPerView:'auto',spaceBetween:24,loop:true,autoplay:calm?false:{delay:3800,disableOnInteraction:false},pagination:{el:'.ex .swiper-pagination',clickable:true}});
if($('.rv'))new Swiper('.rv',{slidesPerView:1,spaceBetween:24,autoplay:{delay:5500},pagination:{el:'.rv .swiper-pagination',clickable:true},breakpoints:{900:{slidesPerView:2}}});
$$('.tabs button').forEach(b=>b.onclick=()=>{$$('.tabs button').forEach(x=>x.classList.toggle('on',x===b));$$('.dish').forEach(d=>{const s=b.dataset.c==='all'||d.dataset.c===b.dataset.c;d.hidden=!s;if(s&&!calm)gsap.from(d,{opacity:0,y:14,duration:.5})})});
const cur=$('#cur');addEventListener('pointermove',e=>gsap.to(cur,{x:e.clientX,y:e.clientY,duration:.15}));$$('a,button').forEach(el=>{el.onmouseenter=()=>cur.classList.add('big');el.onmouseleave=()=>cur.classList.remove('big')});
$$('#resForm,#ctForm,#nlForm').forEach(f=>f.addEventListener('submit',async e=>{e.preventDefault();const m=$('.msg',f),b=$('button.btn',f);m.classList.remove('err');m.textContent='';b.disabled=true;
  try{const r=await fetch(f.action,{method:'POST',body:new FormData(f)}),j=await r.json();if(j.ok){m.textContent=j.message||'Envoyé.';f.reset()}else{m.classList.add('err');m.textContent=j.error||Object.values(j.errors||{}).join(' ')}}
  catch{m.classList.add('err');m.textContent='Connexion impossible. Réessayez ou appelez-nous.'}b.disabled=false}));
$$('.lb').forEach(a=>a.onclick=e=>{e.preventDefault();const o=document.createElement('div');o.style.cssText='position:fixed;inset:0;z-index:97;background:#000e;display:grid;place-items:center;cursor:zoom-out';o.innerHTML=`<img src="${a.href}" style="max-height:90vh;max-width:92vw" alt="">`;o.onclick=()=>o.remove();document.body.append(o)});
