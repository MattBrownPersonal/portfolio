export function validateLocalStorageForDeceased(params, code, firstname, lastname) {
    const elements = params.code?.split("-");
    return (
            elements 
            && (elements.length > 2)
            && (code === elements[0])
            && (firstname === elements[1])
            && (lastname === elements[2])
            );
};

export function validateLocalStorageForSite(params, siteName) {
    return (siteName === params.code);
};

export function getCodeFromParams(params) {
    return params.code?.split("-")[0];
};
