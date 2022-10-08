const configuration = {
  sass: ["./assets/src/scss/style.scss"],
  js: ["./assets/src/js/script.js"],
  browserSync: {
    proxy: "http://dev-setup-setup.test/",
    host: "dev-setup-setup.test",
    watchTask: true,
    open: "external",
    files: [
      "./assets/dist/css/*.min.css",
      "./assets/dist/js/*.min.js",
      "./**/*.php",
    ],
    logLevel: "silent",
  },
};

// eslint-disable-next-line no-undef
module.exports = configuration;