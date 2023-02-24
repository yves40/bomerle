//----------------------------------------------------------------------------
//    properties.js
//
//    Dec 26 2022 Initial
//    Jan 30 2023 Knife images location
//    Feb 04 2023 WIP on knife images administration
//    Feb 13 2023 Image loading delay removed
//    Feb 15 2023 WIP on date input in TWIG form
//    Feb 16 2023 WIP on date input in TWIG form
//    Feb 21 2023 Authentication email stored in client
//    Feb 22 2023 Start work on slider
//    Feb 23 2023 Work on slider
//----------------------------------------------------------------------------
const $props = ( () => {
  const allprops = {
    version : 'bomerle:1.15, Feb 23 2023 ',
    copyright:  'Ratoon software Corporation Inc, Chabreloche France ',
    imagehandler: 'images.js Feb 13 2023, 1.25 ',
    knifehandler: 'knife.js Feb 08 2023, 1.05 ',
    datehandler: 'twigdate.js Feb 16 2023, 1.02 ',
    sliderhandler: 'slider.js Feb 24 2023, 1.06 ',
    knivesimageslocation: '%kernel.project_dir%/public/images/knife'
  }
  let dynprops = {
    'imageloadcount' :0,
    'imageavgloadtime' :0
  }
  return {
    version: () => { return allprops.version; },
    copyright: () => { return allprops.copyright; },
    imagehandler: () => { return allprops.imagehandler; },
    knifehandler: () => { return allprops.knifehandler; },
    datehandler: () => { return allprops.datehandler; },
    sliderhandler: () => { return allprops.sliderhandler; },
    knivesimageslocation: () => { return allprops.knivesimageslocation; },
    imageavgloadtime: () => { return dynprops['imageavgloadtime']; },
    imageloadcount: () => { return dynprops['imageloadcount']; },
    set: (propertyname, value) => { dynprops[propertyname] = value; },
    get: (propertyname) => { return dynprops[propertyname]; },
    save: () => { sessionStorage.setItem('dynprops', JSON.stringify(dynprops)); },
    load: () => {
      let savedprops = sessionStorage.getItem("dynprops");
      if( savedprops !== null) {
        dynprops = JSON.parse(savedprops);
      }
      else {
        sessionStorage.setItem('dynprops', JSON.stringify(dynprops));
        return dynprops;
      }
     },
     saveuseremail: (useremail) => { localStorage.setItem('useremail', useremail); },
     getuseremail: () => { return localStorage.getItem('useremail');}
    }
})();
//  localStorage.setItem('lastlogin', 'yves773340');
