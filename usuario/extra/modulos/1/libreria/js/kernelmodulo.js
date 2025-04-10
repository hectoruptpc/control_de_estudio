//instalado1 = "<div class='text-center'><a onclick='formulas(), primerselect();' href='#formulas' title='Ir a la Seccion de Calculos'><img onmouseover='this.style.opacity=1' onmouseout='this.style.opacity=0.7' src=\"extra/modulos/1/media/imagen/instalado.png\"></a></div>";
//["<div class='text-center'><a onclick='formulas(), primerselect();' href='#formulas' title='Ir a la Seccion de Calculos'><img onmouseover='this.style.opacity=1' onmouseout='this.style.opacity=0.7' src=\"extra/modulos/1/media/imagen/instalado.png\"></a></div>"],

instalado.push(["<div class='col-sm-6 col-md-4'> <div class='thumbnail'> <img onmouseout='this.style.opacity=1' onmouseover='this.style.opacity=0.7' src=\"extra/modulos/1/media/imagen/instalado.svg\"> <div class='caption'> <h3>Modulo Productos Automotrices</h3> <p>Este modulo agrupa una seleccion de formulas de los productos mas utilizados en el mercado automotriz, que le permitira ampliar la gama de productos que usted puede fabricar.</p> <p><a onclick='formulas(), primerselect();' href='#formulas' title='Ir a la Seccion de Calculos' class='btn btn-primary' role='button'>Ir a la Seccion de Calculos</a></p> </div> </div></div>"]);

secciones.push(
    ['Modulo Automotriz']
);

producto.push({
    n: 'Bicarbonato Limpiador',
    az: 1,
    ro: 3,
    am: 0,
    bla: 0,
    c: ' ',
    v: 0,
    t: 'liq',
    grupo: 'Modulo Automotriz'
},
    {
        n: 'Agua limpiadora',
        az: 1,
        ro: 3,
        am: 0,
        bla: 0,
        c: ' ',
        v: 0,
        t: 'liq',
        grupo: 'Modulo Automotriz'
    },
    {
        n: 'Aceite Pulidor',
        az: 1,
        ro: 3,
        am: 0,
        bla: 0,
        c: ' ',
        v: 0,
        t: 'liq',
        grupo: 'Modulo Automotriz'
    }
);

producto.sort(function (a, b) {
    if (a.n > b.n) {
        return 1;
    }
    if (a.n < b.n) {
        return -1;
    }
    // a must be equal to b
    return 0;
});

//componente.push();
componente[700] = {
    n: 'COMP700',
    v: 0,
    t: "liq",
    c: "COMPONENTE 700.",
};
componente[701] = {
    n: 'COMP701',
    v: 0,
    t: "liq",
    c: "COMPONENTE 701.",
};
componente[702] = {
    n: 'COMP702',
    v: 0,
    t: "sol",
    c: "COMPONENTE 702.",
};

function tratamiento_extra() {
    for (ab in componente) {
        if (ab >= 700) {
            ncompo = componente[ab].n;
            titulocompo = ncompo.replace("=", "");
            //componente[ab].n = "<b>" + componente[ab].n+"</b>";
            componente[ab].n = "<b data-toggle='popover' title='<b>" + titulocompo + "</b>' data-content='" + componente[ab].c + "'>" + componente[ab].n + "</b>";
        }
    }
    return componente[ab].n;
}
tratamiento_extra();

function aceite_pulidor() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente[47].v = UI * 22.22 / 100;
    componente[48].v = UI * 33.33 / 100;
    componente[49].v = UI * 66.66 / 100;
    componente[51].v = UI * 88.88 / 100;
    componente[08].v = UI * 11.11 / 100;

    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente[47].n + componente[47].v + lili +
        componente[48].n + componente[48].v + lili +
        componente[49].n + componente[49].v + lili +
        componente[51].n + componente[51].v + lili +
        componente[08].n + componente[08].ext + componente[08].v + liol;


    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>Vierta el óxido de zinc y el almidón en un recipiente de plástico, mezcle con la ayuda de una cuchara. Después, agregue la vaselina y continúe agitando. Coloque la cera en un recipiente de vidrio en baño María. Cuando se derrita la cera, incorpore la fragancia de uso cosmetico y agita con una cuchara hasta que este bien incorporado la fragancia y la cera, efectue ese procedimeinto sin retirar del baño de Maria.</p><p> Por último, incorpore la mezcla del oxido de zinc y almidon al recipiente que esta en el baño de maria y agite durante un tiempo necesario para unir y homogenizar.</p><h4>Envasado y conservación: </h4><p>Retire la mezcla del baño María y viértala de inmediato en el envase de desodorante de barra y tápelo. Debe tener cuidado de no moverlo hasta que solidifique (alrededor de 2 horas). Guárdelo en un lugar fresco y seco. No olvide colocarle una etiqueta con el nombre del producto, la fecha de elaboración y caducidad.</p><h4>Caducidad:</h4><p>El desodorante bien tapado tiene una duración aproximada de seis meses.</p><br><div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Procurar siempre utilizar la tecnica de Baño de Maria, de esa manera evita que su preparacion se queme.</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
    //Linea agregada
    popove();
}