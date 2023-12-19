const data = {
    themes: ['a11y-dark', 'atom-one-dark', 'base16-apathy', 'base16-atelier-cave', 'devibeans', 'far', 'felipec', 'github-dark', 'gradient-dark', 'gradient-light', 'ir-black', 'night-owl', 'nord', 'eee', 'rainbow', 'shades-of-purple', 'srcery', 'tokyo-night-dark', 'base16-decaf', 'base16-outrun-dark'],
    languages: ['arduino', 'apache', 'autohotkey', 'bash', 'sh', 'zsh', 'brainfuck', 'csharp', 'c', 'cpp', 'hpp', 'cmake', 'css', 'crystal', 'elixir', 'erlang', 'go', 'gradle', 'graphql', 'groovy', 'html', 'xhtml', 'http', 'https', 'handlebars', 'haskell', 'haxe', 'hlsl', 'ini', 'toml', 'json', 'java', 'javascript', 'jsx', 'julia', 'less', 'lisp', 'lua', 'makefile', 'markdown', 'moonscript', 'nginx', 'nim', 'nix', 'ocaml', 'monkey', 'php', 'perl', 'postgresql', 'powershell', 'python', 'ruby', 'rust', 'scss', 'sql', 'scala', 'shell', 'swift', 'twig', 'craftcms', 'typescript', 'tsx', 'vbnet', 'vbs', 'vim', 'x86asm', 'yaml', 'yml', 'zephir']
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

