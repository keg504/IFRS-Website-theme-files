!function(e){var t={};function n(c){if(t[c])return t[c].exports;var o=t[c]={i:c,l:!1,exports:{}};return e[c].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,c){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:c})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var c=Object.create(null);if(n.r(c),Object.defineProperty(c,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(c,o,function(t){return e[t]}.bind(null,o));return c},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=559)}({559:function(e,t){!function(){"use strict";var e=document.getElementsByClassName("has-lightbox");Array.from(e).forEach((function(e,t){e.className+=" lightbox-"+t+" ",function(e){var t=document.createElement("div");t.setAttribute("class","coblocks-lightbox");var n=document.createElement("div");n.setAttribute("class","coblocks-lightbox__background");var c=document.createElement("div");c.setAttribute("class","coblocks-lightbox__heading");var o=document.createElement("button");o.setAttribute("class","coblocks-lightbox__close");var r=document.createElement("span");r.setAttribute("class","coblocks-lightbox__count");var a=document.createElement("div");a.setAttribute("class","coblocks-lightbox__image");var i=document.createElement("img"),l=document.createElement("figcaption");l.setAttribute("class","coblocks-lightbox__caption");var s=document.createElement("button");s.setAttribute("class","coblocks-lightbox__arrow coblocks-lightbox__arrow--left");var u=document.createElement("button");u.setAttribute("class","coblocks-lightbox__arrow coblocks-lightbox__arrow--right");var d=document.createElement("div");d.setAttribute("class","arrow-right");var b=document.createElement("div");b.setAttribute("class","arrow-left");var f,m=document.querySelectorAll(".has-lightbox.lightbox-".concat(e," > :not(.carousel-nav) figure img, figure.has-lightbox.lightbox-").concat(e," > img")),g=document.querySelectorAll(".has-lightbox.lightbox-".concat(e," > :not(.carousel-nav) figure figcaption"));c.append(r,o),a.append(i,l),s.append(b),u.append(d),t.append(n,c,a,s,u),m.length>0&&document.getElementsByTagName("BODY")[0].append(t);g.length>0&&Array.from(g).forEach((function(e,t){e.addEventListener("click",(function(){v(t)}))}));Array.from(m).forEach((function(e,t){e.addEventListener("click",(function(){v(t)}))})),s.addEventListener("click",(function(){v(f=0===f?m.length-1:f-1)})),u.addEventListener("click",(function(){v(f=f===m.length-1?0:f+1)})),n.addEventListener("click",(function(){t.style.display="none"})),o.addEventListener("click",(function(){t.style.display="none"}));var p={preloaded:!1,setPreloadImages:function(){p.preloaded||(p.preloaded=!0,Array.from(m).forEach((function(e,t){p["img-".concat(t)]=new window.Image,p["img-".concat(t)].src=e.attributes.src.value,p["img-".concat(t)]["data-caption"]=m[t]&&m[t].nextElementSibling?function(e){for(var t=e.nextElementSibling;t;){if(t.matches("figcaption"))return t.innerHTML;t=t.nextElementSibling}return""}(m[t]):""})),document.onkeydown=function(e){if(void 0!==t&&"none"!==t)switch((e=e||window.event).keyCode){case 27:o.click();break;case 37:case 65:s.click();break;case 39:case 68:u.click()}})}};function v(e){p.setPreloadImages(),f=e,t.style.display="flex",n.style.backgroundImage="url(".concat(p["img-".concat(f)].src,")"),i.src=p["img-".concat(f)].src,l.innerHTML=p["img-".concat(f)]["data-caption"],r.textContent="".concat(f+1," / ").concat(m.length)}}(t)}))}()}});