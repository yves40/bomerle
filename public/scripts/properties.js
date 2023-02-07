//----------------------------------------------------------------------------
//    properties.js
//
//    Dec 26 2022 Initial
//    Jan 30 2023 Knife images location
//    Feb 04 2023 WIP on knife images administration
//----------------------------------------------------------------------------
const $props = ( () => {
  const allprops = {
    version : 'bomerle:1.06, Feb 07 2023 ',
    copyright:  'Ratoon software Corporation Inc, Chabreloche France ',
    imagehandler: 'images.js Dec 27 2022, 1.23 ',
    knifehandler: 'knife.js Feb 04 2023, 1.04 ',
    knivesimageslocation: '%kernel.project_dir%/public/images/knife'
  }
  let dynprops = {
    'imageloadingdelay' : 400,
    'imageloadcount' :0
  }
  return {
    version: () => { return allprops.version; },
    copyright: () => { return allprops.copyright; },
    imagehandler: () => { return allprops.imagehandler; },
    knifehandler: () => { return allprops.knifehandler; },
    knivesimageslocation: () => { return allprops.knivesimageslocation; },
    imageloadingdelay: () => { return dynprops['imageloadingdelay']; },
    imageloadcount: () => { return dynprops['imageloadcount']; },
    set: (propertyname, value) => { dynprops[propertyname] = value; },
    get: (propertyname) => { return dynprops[propertyname]; },
    save: () => { sessionStorage.setItem('dynprops', JSON.stringify(dynprops)); },
    load: () => {
      let savedprops = sessionStorage.getItem("dynprops");
      if( savedprops !== null) {
        dynprops = JSON.parse(savedprops);
      }
     }
  }
})();
//  localStorage.setItem('lastlogin', 'yves773340');
