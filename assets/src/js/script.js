import $ from "jquery";

if ($('#content').hasClass('page-admission')) {
  import('./admission-script.js').then((module) => {
    const admissionModule = module.default; // Access the default property
    admissionModule.init()
  });
}