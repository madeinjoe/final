import $ from "jquery";

if ($('#content').hasClass('page-admission')) {
  import('./admission-script.js').then((module) => {
    const admissionModule = module.default; // Access the default property
    admissionModule.init()
  });
}

if ($('#content').hasClass('page-contact')) {
  import('./contact-script.js').then((module) => {
    const contactModule = module.default; // Access the default property
    contactModule.init()
  });
}

if ($('input[name="item"]').length > 0 || $('.product').length > 0 || $('.products').length > 0) {
  import('./shop-script.js').then((module) => {
    const shopModule = module.default; // Access the default property
    shopModule.init()
  });
}
