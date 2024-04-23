/*----------------------------------------------------------------------------
    Sliderzoom
----------------------------------------------------------------------------*/

  import Logger  from './logger.js';
  import $props  from '../properties.js';
  import Slider from './slider.js';

  export default class Sliderzoom extends Slider {

  constructor(container, timing = 2, description = '', allimages, slidertype = 'SHOW') {
    // Init
    super(container, timing, description, allimages, slidertype);
  }
}