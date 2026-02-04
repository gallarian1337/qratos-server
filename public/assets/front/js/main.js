window.onload = () => {
    const pathname = window.location.pathname;

    if (pathname === "/connexion") {
        import("./controller/front/login.js")
            .then(module => {
                module.login();
            })
        ;
    }
};
