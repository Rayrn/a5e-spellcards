// any CSS you import will output into a single css file (app.css in this case)
import './styles/global.scss';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');

require('bootstrap');

$(function () {
    $('[data-toggle="popover"]').popover();
});
