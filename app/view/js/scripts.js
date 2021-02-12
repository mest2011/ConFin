function trocaPagina(pagina) {
    window.location.href = pagina;
}

function emojiUnicode (emoji) {
    var comp;
    if (emoji.length === 1) {
        comp = emoji.charCodeAt(0);
    }
    comp = (
        (emoji.charCodeAt(0) - 0xD800) * 0x400
      + (emoji.charCodeAt(1) - 0xDC00) + 0x10000
    );
    if (comp < 0) {
        comp = emoji.charCodeAt(0);
    }
    return comp.toString("16");
};

function funcaoIndisponivel() {
    toastr.warning('Função indisponivel! Estamos trabalhando para disponibiliza-la o quanto antes!', 'Atenção:', {
        timeOut: 3000
    });
}