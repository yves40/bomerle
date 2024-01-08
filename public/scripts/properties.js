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
//    Jun 05 2023 Work on slider class
//    Sep 21 2023 WIP on email for contact requests
//    Jan 07 2024 Some tests 
//    Jan 08 2024 Some tests 
//----------------------------------------------------------------------------
const $props = ( () => {
  const allprops = {
    version : 'bomerle:1.24, Jan 08 2024 ',
    copyright:  'Ratoon software Corporation Inc, Chabreloche France ',
    imagehandler: 'images.js Feb 13 2023, 1.25 ',
    knifehandler: 'knife.js Feb 08 2023, 1.05 ',
    datehandler: 'twigdate.js Feb 16 2023, 1.02 ',
    sliderhandler: 'slider.js Feb 24 2023, 1.06 ',
    sliderclass: 'slider Class.js Jun 05 2023, 1.00 ',
    logshandler: 'logs.js Mar 26 2023, 1.08 ',
    // Some locations
    rootimages: '/images',
    knifeimages_directory: '/images/knife',
    slideshowimages_directory: '/images/slideshow',
    categoryimages_directory: '/images/category',
    gif_directory: '/images/gif',
    svg_directory: '/images/svg',
    // Default category image when none has been set
    defaultcategoryimage: 'BastosBG3.webp',

    logspagesize: 20,
    logspagedateoffset: 31,
    slidingtime: 300,
    inputprocessingdelay: 1500,    // Wait 1.5 sec when the user key in a filter
    administratoremail: 'yves77340@gmail.com'
  }
  let dynprops = {
    'imageloadcount' :0,
    'imageavgloadtime' :0,
    'applang': 'fr'
  }
  return {
    version: () => { return allprops.version; },
    copyright: () => { return allprops.copyright; },
    imagehandler: () => { return allprops.imagehandler; },
    knifehandler: () => { return allprops.knifehandler; },
    datehandler: () => { return allprops.datehandler; },
    sliderhandler: () => { return allprops.sliderhandler; },
    sliderclass: () => { return allprops.sliderclass; },
    logshandler: () => { return allprops.logshandler; },

    knifeimageslocation: () => { return allprops.knifeimages_directory; },
    slideimageslocation: () => { return allprops.slideshowimages_directory; },
    categoryimageslocation: () => { return allprops.categoryimages_directory; },
    svgimageslocation: () => { return allprops.svg_directory; },
    gifimageslocation: () => { return allprops.gif_directory; },
    rootimageslocation: () => { return allprops.rootimages; },
    defaultcategoryimage: () => { return allprops.defaultcategoryimage; },

    imageavgloadtime: () => { return dynprops['imageavgloadtime']; },    
    imageloadcount: () => { return dynprops['imageloadcount']; },
    applang: () => { return dynprops['applang']; },
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
     getuseremail: () => { return localStorage.getItem('useremail');},
     getLogsPageSize: () => { return allprops.logspagesize; },
     getLogsDateOffest: () => { return allprops.logspagedateoffset; },
     getSlidingTime: () => { return allprops.slidingtime; },
     getInputDelay: () => { return allprops.inputprocessingdelay; },
     getAdministratorEmail: () => { return allprops.administratoremail; }
    }
})();

