var _=Object.defineProperty,v=Object.defineProperties;var b=Object.getOwnPropertyDescriptors;var u=Object.getOwnPropertySymbols;var w=Object.prototype.hasOwnProperty,$=Object.prototype.propertyIsEnumerable;var x=(e,t,s)=>t in e?_(e,t,{enumerable:!0,configurable:!0,writable:!0,value:s}):e[t]=s,f=(e,t)=>{for(var s in t||(t={}))w.call(t,s)&&x(e,s,t[s]);if(u)for(var s of u(t))$.call(t,s)&&x(e,s,t[s]);return e},y=(e,t)=>v(e,b(t));import{d as c,c as p,a as i,t as r,F as d,o as a,r as k,b as C,e as S,f as q,g as j}from"./vendor.978221e7.js";const A=e=>({baseURL:e,headers:{"X-Requested-With":"XMLHttpRequest"}}),O=async(e,t,s,n)=>{try{const o=await e.post(t,s);n&&o.data&&n(o.data)}catch(o){console.error("xhr",o)}};var g=(e,t)=>{const s=e.__vccOpts||e;for(const[n,o]of t)s[n]=o;return s};const B=c({props:{section:{type:Object,required:!0},api:{type:Object,required:!0}},methods:{async syncCollection(){let e=y(f({},this.section),{sectionId:this.section.id,[this.api.csrf.name]:this.api.csrf.value});console.log(this.api),await O(this.api.client,this.api.action,e,t=>{console.table(t)})}}}),L={class:"px-6 py-4 whitespace-nowrap flex items-center"},I={class:"px-6 py-4 whitespace-nowrap flex items-center"},R={class:"px-6 py-4 whitespace-nowrap flex items-center"},E={class:"px-6 py-4 whitespace-nowrap flex items-center"},F={class:"px-6 py-2 border-b border-gray-100"};function N(e,t,s,n,o,h){return a(),p(d,null,[i("div",L,r(e.section.index),1),i("div",I,r(e.section.name),1),i("div",R,r(e.section.type),1),i("div",E,r(e.section.entryCount),1),i("div",F,[i("button",{type:"button",class:"cursor-pointer inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500",onClick:t[0]||(t[0]=l=>e.syncCollection())}," Sync ")])],64)}var T=g(B,[["render",N]]);const U=c({components:{"list-item-section":T},props:{sections:{type:Object,required:!0},apiConfig:{type:Object,required:!0}},data:()=>({api:null}),methods:{createApi(){const e={client:axios.create(A(this.apiConfig.baseUrl)),csrf:this.apiConfig.csrf,action:this.apiConfig.action};this.api=e}},async created(){await this.createApi()}}),V={class:"mb-16 rounded-tr-sm rounded-tl-sm"},X=C('<div class="grid grid-cols-5 rounded-tr-md rounded-tl-md bg-gray-100 mb-4"><div class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> Index </div><div class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> Section </div><div class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> Type </div><div class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> Total Entries </div><div class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"> Sync </div></div>',1),D={class:"grid grid-cols-5"},G=i("div",{class:"bg-emerald-200 py-8 px-4 max-w-xl"},null,-1),H={class:"bg-sky-200 py-8 px-4 max-w-xl"},M={class:"bg-rose-200 my-8 py-8 px-4 max-w-xl"};function W(e,t,s,n,o,h){const l=S("list-item-section");return a(),p(d,null,[i("section",V,[X,i("div",D,[(a(!0),p(d,null,k(e.sections,m=>(a(),q(l,{section:m,key:m.id,api:e.api},null,8,["section","api"]))),128))])]),G,i("div",H,r(e.sections),1),i("div",M,r(e.apiConfig),1)],64)}var z=g(U,[["render",W]]);const J=c({components:{"grid-sections":z}}),K=async()=>j(J).mount("#typesense-collections");K().then(e=>{});
//# sourceMappingURL=typesense-collections.b7a924b8.js.map
