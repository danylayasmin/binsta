const data = {
    themes: ['a11y-dark', 'atom-one-dark', 'base16-apathy', 'base16-atelier-cave', 'devibeans', 'far', 'felipec', 'github-dark', 'gradient-dark', 'gradient-light', 'ir-black', 'night-owl', 'nord', 'eee', 'rainbow', 'shades-of-purple', 'srcery', 'tokyo-night-dark', 'base16-decaf', 'base16-outrun-dark'],
    languages: ["bash","c","cpp","csharp","css","diff","go","graphql","ini","java","javascript","json","kotlin","less","lua","makefile","markdown","objectivec","perl","php","php-template","plaintext","python","python-repl","r","ruby","rust","scss","shell","sql","swift","typescript","vbnet","wasm","xml","yaml"]
};

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

const selectedTheme = getCookie('theme');

if (selectedTheme && data.themes.includes(selectedTheme)) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    if (selectedTheme.startsWith('base16')) {
        link.href = `https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/styles/base16/${selectedTheme.substring(7)}.min.css`;
    } else {
        link.href = `https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/styles/${selectedTheme}.min.css`;
    }
    document.head.appendChild(link);
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('pre code').forEach((block) => {
        hljs.highlightElement(block);
    });
});