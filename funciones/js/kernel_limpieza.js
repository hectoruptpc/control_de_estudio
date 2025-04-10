// VARIABLES GLOBALES
deposito = "LFI0432016600887";
version = "5.0.0"
ano = "2020"
cabecera = "<div class = 'container'> <div class = 'row well well-sm'> <div class = 'hidden-xs col-sm-4 col-md-6 text-center'> <h2>Descripcion</h2> </div> <div class = 'hidden-xs col-sm-8 col-md-6 text-center'> <h2>Archivo</h2> </div> </div>";
mensajeactualizacion = "https://local.jesuministrosymas.com.ve/publi/mensajes/cursos/limpieza500/";
mensajeextra = "https://local.jesuministrosymas.com.ve/publi/mensajes/cursos/limpieza500/extra/";

foot = '';

//DEFINIENDO VARIABLES GLOBALES
i = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Podra Imprimir' class = 'glyphicon glyphicon-file glyphicon-print' aria-hidden = 'true'> </span>";
c = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Calculos Automatizado' class = 'glyphicon glyphicon-file glyphicon-transfer' aria-hidden = 'true'> </span>";
a = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Contiene Archivos PDF' class = 'glyphicon glyphicon-file glyphicon-film' aria-hidden = 'true'> </span>";
e = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Consulte las Extensiones Instaladas' class = 'glyphicon glyphicon-file glyphicon-eye-open' aria-hidden = 'true'> </span>";
v = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Contiene Videos' class = 'glyphicon glyphicon-film' aria-hidden = 'true'> </span>";
l = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Contiene Links' class = 'glyphicon glyphicon-link' aria-hidden = 'true'> </span>";
h = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Cargar Inicio del Curso' class = 'glyphicon glyphicon-home' aria-hidden = 'true'> </span>";

imprimirxs = "<p class='text-right'><a class='btn btn-danger btn-xs' href='javascript:window.print(); void 0;'>Imprimir " + i + "</a></p>";

br = "<br>";
olli = "<ol><li>";
liol = "</li></ol>";
olc = "</li></ol>";
ulli = "<ul><li>";
liul = "</li></ul>";
ulc = "</li></ul>";
eli = "<li>";
lic = "</li>";
lili = lic + eli;

ml = " Mililitros";
mls = " Mililitro";
li = " Litros";
lis = " Litro";
gr = " Gramos";
grs = " Gramo";
mg = " Miligramos";
mgs = " Miligramo";
kl = " Kilogramos";
kls = " Kilogramo";

tca = "<b>Para hacer ";
tcc = " se requieren:</b>";
sup = 'Seleccione un Producto...';

secciones = [
    'Formulas Incluidas'
];


// ARREGLO DE PRODUCTOS
producto = [
    /*
RESUMEN DE ROMBO NFPA
    a: Abreviacion Formula
    n: Nombre
    v: valor inicial
    t: tipo de producto true para liq y false para sol
    az: azul
        0 = SIN RIESGO
        1 = POCO PELIGROSO
        2 = PELIGROSO
        3 = MUY PELIGROSO
        4 = MORTAL
    ro: rojo
        0 = NO SE INFLAMA
        1 = SOBRE 93°
        2 = DEBAJO DE 93°
        3 = DEBAJO DE 37°
        4 = DEBAJO DE 25°
    am: amarillo
        0 = ESTABLE
        1 = INESTABLE EN CASO DE CALENTAMIENTO
        2 = INESTABLE EN CASO DE CAMBIO QUIMICO VIOLENTO
        3 = PUEDE EXPLOTAR EN CASO DE CHOQUE O CALENTAMIENTO
        4 = PUEDE EXPLOTAR SUBITAMENTE
    bla: blanco
        0 = OXIDANTE
        1 = CORROSIVO
        2 = RADIOACTIVO
        3 = NO USAR AGUA
        4 = RIESGO BIOLOGICO
        5 = NADA

    */
    { n: 'Jabon en Pasta', az: 1, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
    { n: 'Ambientador', az: 2, ro: 1, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Cloro', az: 2, ro: 0, am: 1, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Cera Para Pulir Muebles', az: 1, ro: 1, am: 0, bla: 3, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
    { n: 'Cera Para Pulir Carros', az: 1, ro: 1, am: 0, bla: 3, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
    { n: 'Limpia Hornos', az: 0, ro: 0, am: 0, bla: 1, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Limpiador de Pocetas', az: 0, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Antitranspirante en Barra', az: 2, ro: 0, am: 2, bla: 1, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
    { n: 'Pino Limon', az: 2, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Cera Para Pisos Autobrillante', az: 1, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Suavizante Para Telas', az: 1, ro: 0, am: 0, bla: 1, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Shampoo Pert Plus', az: 1, ro: 0, am: 0, bla: 1, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Shampoo Para Perros', az: 1, ro: 0, am: 0, bla: 1, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Shampoo Pantene', az: 1, ro: 0, am: 0, bla: 1, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Limpia Vidrio', az: 2, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Gel Fijador de Cabello', az: 1, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
    { n: 'Desinfectante Para Pisos', az: 1, ro: 0, am: 0, bla: 5, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Detergente Para Ropa', az: 0, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Lavaplatos Liquido', az: 0, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Base Para Shampoo', az: 2, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Detersin K', az: 2, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Acido Para Pisos de Granito', az: 2, ro: 0, am: 1, bla: 1, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Talco Para Pies', az: 1, ro: 0, am: 0, bla: 5, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
    { n: 'Jabon Liquido Para el Cuerpo', az: 1, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Aceite Para Bebe', az: 2, ro: 2, am: 0, bla: 3, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Removedor de Esmalte', az: 2, ro: 3, am: 3, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Jabon Liquido Para las Manos', az: 1, ro: 0, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Perfumes', az: 1, ro: 3, am: 0, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Aceite Para Aromaterapia', az: 2, ro: 2, am: 0, bla: 3, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Aceite Para Baño', az: 2, ro: 2, am: 0, bla: 3, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Abrillantador Automotriz Universal', az: 2, ro: 2, am: 0, bla: 3, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Limpiador de Metales en Polvo', az: 0, ro: 0, am: 0, bla: 5, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
    { n: 'Desengrasante Universal', az: 1, ro: 0, am: 0, bla: 5, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Formula Anticorrosiva en Aceite', az: 2, ro: 2, am: 0, bla: 3, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Alcohol Desinfectante en Gel', az: 1, ro: 3, am: 0, bla: 5, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Blanqueador Concentrado', az: 2, ro: 0, am: 1, bla: 0, c: ' ', v: 0, t: 'liq', grupo: 'Formulas Incluidas' },
    { n: 'Crema Para Limpiar Zapatos', az: 2, ro: 2, am: 1, bla: 5, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
    { n: 'Crema Removedora de Pintura y Grasa Para las Manos', az: 1, ro: 0, am: 0, bla: 5, c: ' ', v: 0, t: 'sol', grupo: 'Formulas Incluidas' },
];

producto.sort(function (a, b) {
    if (a.n > b.n) {
        return 1;
    }
    if (a.n < b.n) {
        return -1;
    }
    return 0;
});


// ARREGLO PARA COMPONENTES
componente = new Array();
/*
a: Abreviacion Formula
n: Nombre con espacio igual espacio ( = )
v: valor inicial
t: tipo de componente true para liq y false para sol
*/
componente['00'] = {
    n: 'Agua H<sub>2</sub>O = ',
    v: 0,
    t: "liq",
    c: "El agua (H<sub>2</sub>O) es un compuesto químico inorgánico formado por dos átomos de hidrógeno (H) y uno de oxígeno (O).​ Se encuentra en la naturaleza en sus tres estados y fue clave para su formación. Hay que distinguir entre el agua potable y el agua pura, pues la primera es una mezcla que también contiene sales en solución; es por esto que en laboratorio y en otros ámbitos se usa agua destilada.",
};
componente['01'] = {
    n: 'Texapon (Lauril Sulfato Sodico) = ',
    v: 0,
    t: "sol",
    c: "El lauril éter sulfato sódico es una excelente sustancia para la preparación de champús y geles de baño. Debido a la gran calidad detergente y de limpieza se puede emplear en el ramo químico-técnico para la elaboración de agentes enjuagantes y de limpieza de líquidos.",
};
componente['02'] = {
    n: 'Alcohol Cetilico -  CH<sub>3</sub>(CH<sub>2</sub><sub>15</sub>OH = ',
    v: 0,
    t: "liq",
    c: "El alcohol cetílico, también conocido como 1-hexadecanol o alcohol palmitílico, es un alcohol graso con la fórmula molecular CH<sub>3</sub>(CH<sub>2</sub><sub>15</sub>OH. A temperatura ambiente el alcohol cetílico toma la forma de cera blanca o en copos.",
};
componente['03'] = {
    n: 'Aceite de Ricino  = ',
    v: 0,
    t: "liq",
    c: "El aceite de ricino, en muchas ocasiones mal traducido como aceite de castor por su denominación en inglés (castor oil), se obtiene a partir de las semillas de la planta Ricinus communis, que contienen aproximadamente un 40-50% de su peso del aceite. El aceite a su vez contiene el 70-77% de los triglicéridos del ácido ricinoleico. A diferencia de las propias semillas, no es tóxico.",
};
componente['04'] = {
    n: 'Aceite Mineral  = ',
    v: 0,
    t: "liq",
    c: "Un aceite mineral es un subproducto líquido de la destilación del petróleo desde el petróleo crudo. Un aceite mineral en este sentido es un aceite transparente incoloro compuesto típicamente de alcanos (típicamente de 15 a 40 carbonos)1​y parafina cíclica. Tiene una densidad de unos 0,8 g/cm³. El aceite mineral es una sustancia de relativamente bajo precio y se producen en grandes cantidades.El aceite mineral está disponible en grados ligeros y pesados.Tiene muchos usos.La mayoría se utilizan como lubricante, refrigerante o por sus propiedades eléctricas.Básicamente hay tres clases de refinados ", };
componente['05'] = {
    n: 'Acido Acetico (Vinagre Blanco) - CH<sub>3</sub>COOH = ',
    v: 0,
    t: "liq",
    c: "También llamado ácido metilcarboxílico o ácido etanoico, puede encontrarse en forma de ion acetato. Se encuentra en el vinagre, y es el principal responsable de su sabor y olor agrios. Es el segundo de los ácidos carboxílicos, después del ácido fórmico o metanoico, que solo tiene un carbono, y antes del ácido propanoico, que ya tiene una cadena de tres carbonos.",
};
componente['06']  = {
    n: 'Carboximetil Celulosa CMC RnOCH<sub>2</sub>-COOH = ',
    v: 0,
    t: "sol",
    c: "La carboximetilcelulosa (CMC) o carmelosa es un compuesto orgánico, derivado de la celulosa, compuesto por grupos carboximetil, enlazados a algunos grupos hidroxilo, presente en polímeros de la glucopiranosa. A menudo se utiliza como sal, es decir, como carboximetilcelulosa de sodio, también llamada carmelosa sódica, que se utiliza como medicamento para el alivio de los síntomas de la irritación y la sequedad ocular.", };
componente['07']  = {
    n: 'Color = ',
    v: 0,
    ext: ' Puede ser al gusto pero para esta cantidad que desea preparar se recomiendan: ',
    t: "liq",
    p: " Se recomienda utilizar color Verde Manzana Vegetal. ",
    s: " Se recomienda utilizar Colorante Vegetal Azul. ",
    c: "Hay distintos tipos de colorantes o pigmentos y debe tener cuidado, normalmente las comercializan hidrosolubles (solubles en agua), liposolubles (solubles en grasas o aceites) e hiposodicas (Son hidrosolubles pero no pierden efecto al contacto con el hipoclorito), cada una es utilizada en productos particulares. Pueden ser polvos solidos o ya diluidos en sus respectivos medios de dilucion en concentraciones que van de 60% a 80%",
    grasa: " Tome en cuenta que en este producto debe utilizar Color a la Grasa. ", };
componente['08']  = {
    n: 'Fragancia = ',
    v: 0,
    ext: ' Puede ser al gusto pero para esta cantidad que desea preparar se recomiendan: ',
    t: "liq",
    p: " Se recomienda utilizar fragancia extracto de Pert Plus ",
    rosa: " Se recomienda utilizar fragancia extracto de rosa ",
    s: " Se recomienda utilizar Colorante Vegetal Azul ",
    h: "Perfume Hiposodico = ",
    l: " Se recomienda utilizar fragancia Extracto de Limon ",
    tal: " Usted puede agregar mas o menos cantidad de la indicada, se recomienda utilizar fragancia olor bebe, olor talco para bebe. ",
    c: "Hay distintos tipos de fragancias y debe tener cuidado, normalmente las comercializan hidrosolubles (solubles en agua), liposolubles (solubles en grasas o aceites) e hiposodicas (Son hidrosolubles pero no pierden efecto al contacto con el hipoclorito), cada una es utilizada en productos particulares.",
};
componente['09']  = {
    n: 'Base Para Shampoo = ',
    v: 0,
    t: "liq",
    c: "Producto empleado como base para preparar otros productos.", };
componente['10']  = {
    n: 'Tensoactivo Aniónico = ',
    v: 0,
    t: "liq",
    c: "Esta clase de tensioactivos incluye tanto alquilfosfatos como alquil éter fosfatos. Estos tensioactivos se producen por la reacción de alcoholes grasos con alguno de estos dos posibles agentes fosfatantes: ácido fosfórico y pentóxido de fósforo. El grupo polar tiende a hacer el jabón soluble en agua (hidrófilo) mientras que la porción no polar (hidrocarburo) tiende a hacerlo soluble en grasas (hidrófobo o lipófilo). Las sustancias que disminuyen la tensión superficial de un líquido o la acción entre dos líquidos, se conoce como agentes tensoactivos.",
    };
componente['11'] = {
    n: 'Cloruro de Sodio (Sal Comun) - NaCl = ',
    v: 0,
    t: "sol",
    c: "Este producto es conocido normalmente como Sal Comun, Sal de Mar, el mismo que se utiliza en la cocina, este producto le brinda el 70% de espesor al shampoo.",
};
componente['12'] = {
    n: 'Complejo Provitamínico B-5 = ',
    v: 0,
    t: "sol",
    c: "El ácido pantoténico es una vitamina, también conocida como vitamina B5. Se encuentra ampliamente distribuida tanto en el reino vegetal como animal y abunda en la carne, las verduras, los granos de cereales, las legumbres, los huevos y la leche. La vitamina B5 está disponible comercialmente como el isómero D del ácido pantoténico, y también como dexpantenol y como pantotenato de calcio, que son productos químicos sintetizados en el laboratorio a partir del isómero D del ácido pantoténico. El ácido pantoténico es usado frecuentemente en combinación con otras vitaminas B en formulaciones de vitaminas del complejo B. El complejo de vitaminas B generalmente incluye la vitamina B1 (tiamina), la vitamina B2 (riboflavina), la vitamina B3 (niacina / niacinamida), la vitamina B5 (ácido pantoténico), la vitamina B6 (piridoxina), la vitamina B12 (cianocobalamina) y el ácido fólico. Sin embargo, algunos productos no contienen todos estos ingredientes y algunos pueden incluir otros, como la biotina, el ácido para - aminobenzoico(PABA), el bitartrato de colina y el inositol.", };
componente['13'] = {
    n: 'Vitamina E = ',
    v: 0,
    t: "sol",
    c: "La vitamina E es un antioxidante. Eso significa que protege el tejido corporal del daño causado por sustancias llamadas radicales libres, que pueden dañar células, tejidos y órganos. Se cree que juegan un papel en ciertas afecciones relacionadas con el envejecimiento",
};
componente['14'] = {
    n: 'Preservante Acido Ascorvico - C<sub>6</sub>H<sub>8</sub>O<sub>6</sub> = ',
    v: 0,
    t: "sol",
    c: "El ácido ascórbico es un cristal incoloro, inodoro, sólido, soluble en agua, con un sabor ácido. Es un ácido orgánico, con propiedades antioxidantes, proveniente del azúcar. En humanos, primates y cobayas, entre otros, la vitamina C(enantiómero L del ácido ascórbico) no se sintetiza, por lo que debe ingerirse a través de los alimentos.Esto se debe a la ausencia de la enzima L - gluconolactona oxidasa, que participa en la ruta del ácido úrico. ", };
componente['15']  = {
    n: 'Euperland PK = ',
    v: 0,
    t: "sol",
    c: "Es una dispersión de agentes nacarados y un sulfato de éter de alcohol graso. Se puede mezclar con todos los surfactantes aniónicos y la mayoría de las materias primas para detergentes. Es adecuado para la producción de preparaciones cosméticas de tipo emulsión que tiene un brillo nacarado, tales como champús y baños de burbujas. Debido a su gran fracción de sustancias abrillantadores, Euperlan es particularmente adecuado para cremas de baño de burbujas tanto como un agente de nacarado y el componente reengrasante.", };
componente['16']  = {
    n: 'Genamin = ',
    v: 0,
    t: "sol",
    c: "Se encuentra principalmente en el aceite de coco y de laurel; se obtiene por cristalización fraccionada de aceite de la planta después de su extracción con disolvente, este producto le da suavidad a las fórmulas para jabones y productos de belleza y salud.",
    };
componente['17']  = {
    n: 'Glicerina - C<sub>3</sub>H<sub>8</sub>O<sub>3</sub> = ',
    v: 0,
    t: "liq",
    c: "La glicerina vegetal, o glicerol, es un líquido transparente inodoro elaborado de los aceites de las plantas, especialmente aceite de palma, soya o aceite de coco. Los aceites de palma y de coco son mezclas naturales de triglicéridos; cada triglicérido está compuesto por tres ácidos grasos esterificados con glicerina.",
    };
componente['18']  = {
    n: 'Genapol liquido al 12,5% v/v = ',
    v: 0,
    t: "liq",
    c: "EL lauril éter sulfato de sodio, o SLES, es un detergente y surfactante encontrado en numerosos productos del cuidado personal (jabón, champú, pasta de dientes). SLES es un económico y muy efectivo agente formador de espuma. SLES, SLS y ALS son surfactantes usados en productos cosméticos por sus propiedades limpiantes y emulsificantes.",
    };
componente['19']  = {
    n: 'Creolina = ',
    v: 0,
    t: "liq",
    c: "Creolina fue una marca comercial registrada de William Pearson srl Génova, y se corresponde con un producto desinfectante, una mezcla de compuestos químicos, cuyos nombres, empresa y producto, se inspiran en el desinfectante inglés preparado por William Pearson LTD. Desde 1888 a la fecha la creolina ha experimentado varios cambios. La composición original era aceite de alquitrán, jabones, soda cáustica, y muy poca agua. El principal componente activo (y tóxico) son fenoles (26%), aceites neutrales de alquitrán de hulla (51%) , jabones (13%) y agua (10 %).4​ La principal toxicidad de este producto es la de los fenoles, que son tóxicos celulares inespecíficos provocando daño a nivel gastrointestinal, hepático, renal, neurológico, etc. La creolina es un desinfectante natural que se extrae de la destilación seca de la madera. Este procedimiento consiste en destilar la madera en grandes autoclaves. De los vapores que se desprenden de esta destilación se extrae el aguarraz vegetal o esencia de trementina. El residuo que queda en el recipiente de la autoclave es una masa de color oscuro, de aspecto siruposo, que se denomina creosota. Está compuesta principalmente por fenol (ácido fénico) y cresol (ácido cresílico). Es un desinfectante muy poderoso, de origen natural, y que se emplea para elaborar diferentes compuestos destinados a la limpieza y desinfección.",
    };
componente['20']  = {
    n: 'Amitraz - C<sub>19</sub>H<sub>23</sub>N<sub>3</sub> = ',
    v: 0,
    t: "liq",
    c: "Amitraz es un medicamento antiparasitario. Algunos productos registrados son Preventic (Veterquímica), amitraz (Eximerck) y en asociación con piretroides Vetancid (Vetanco).", };
componente['21']  = {
    n: 'Dimeticona o Polidimetilsiloxano - (C<sub>2</sub>H<sub>6</sub>OSi)<sub>n</sub> = ',
    v: 0,
    t: "liq",
    c: "El polidimetilsiloxano o PDMS o dimeticona es el polímero lineal del dimetilsiloxano. Pertenece al grupo de los compuestos de organosilicio, sustancias comúnmente conocidas como siliconas.​",
    };
componente['22']  = {
    n: 'D Pantenol (Vitamina B5) = ',
    v: 0,
    t: "liq",
    c: "Pantenol es un líquido viscoso transparente a temperatura ambiente, pero las sales de ácido pantoténico (por ejemplo pantotenato sódico) son polvos (típicamente blanco). Es soluble en agua, etanol y propilenglicol, soluble en dietiléter y cloroformo, y poco soluble en glicerina.",
    };
componente['23']  = {
    n: 'Nonilfenol o Arkopal - C<sub>15</sub>H<sub>24</sub>O = ',
    v: 0,
    t: "liq",
    c: "Los nonilfenoles son una familia de compuestos orgánicos relacionados de la familia de los alquilfenoles. Esta serie de sustancias son ingredientes comunes en numerosos detergentes, aunque la legislación europea​ los ha prohibido recientemente debido a su impacto sobre el medio ambiente y la salud humana.",
    };
componente['24']  = {
    n: 'Alcohol Isopropilico (2-Propanol) - C<sub>3</sub>H<sub>8</sub>O = ',
    v: 0,
    t: "liq",
    c: "El 2-propanol, también llamado alcohol isopropílico o Propan-2-ol en la nomenclatura IUPAC, es un alcohol incoloro, inflamable, con un olor intenso y muy miscible con el agua. Es un isómero del 1-propanol y el ejemplo más sencillo de alcohol secundario, donde el carbono del grupo alcohol está unido a otros dos carbonos. Cuando este alcohol se oxida se convierte en acetona ya que los alcoholes secundarios se convierten en cetonas (a diferencia de los alcoholes primarios que se convierten en aldehídos).", };
componente['25']  = {
    n: 'Amoniaco o Hidroxido de Amonio - NH<sub>3</sub> = ',
    v: 0,
    t: "liq",
    c: "El amoníaco (NH<sub>3</sub>) es un compuesto formado por nitrógeno e hidrógeno. Tanto el amoníaco como el hidróxido de amonio son compuestos muy comunes que se encuentran en estado natural en el medio ambiente (en el aire, el agua y el suelo) y en todas las plantas y animales, incluidos los humanos." };
componente['26']  = {
    n: 'Butil Cellosolve = ',
    v: 0,
    t: "liq",
    c: "Líquido incoloro, de olor característico. Los cellosolves son monoéteres derivados del etilenglicol. Son utilizados mayormente como solventes, pero pueden ser utilizados también como aditivos para anticongelantes, intermediario en la producción de polímeros, plastificante para plásticos, lacas y barnices, manufactura de explosivos, recubrimientos base agua, desinfectantes, etc.",
    };
componente['27']  = {
    n: 'Alcohol Desnaturalizado = ',
    v: 0,
    t: "liq",
    c: "Alcohol etílico mezclado con ciertos productos que le comunican sabor desagradable y lo inutilizan para la bebida, pero no para sus aplicaciones industriales.",
    };
componente['28']  = {
    n: 'Trietanolamina (TEA) - C<sub>6</sub>H<sub>15</sub>NO<sub>3</sub> = ',
    v: 0,
    t: "liq",
    c: "La trietanolamina, 2,2´,2´´-nitrilotrietanol ó trihidroxietilamina (frecuentemente abreviada como TEA o trieta en el mercado de productos químicos) es un compuesto químico orgánico formado, principalmente, por una amina terciaria y tres grupos hidróxilos;su fórmula química es C<sub>6</sub>H<sub>15</sub>NO<sub>3</sub>. Como otras aminas, la trietanolamina actúa como una base química débil debido al par solitario de electrones en el átomo de nitrógeno. Se presenta como un líquido viscoso (aunque cuando es impuro puede presentarse como un sólido, dependiendo de la temperatura), límpido, de color amarillo pálido ó incoloro, poco higroscópico y volátil, totalmente soluble en agua y miscible con la mayoría de los solventes orgánicos oxigenados. Posee un olor amoniacal suave.",
    };
componente['29']  = {
    n: 'Carbopol CH<sub>2</sub>-CH(COOH) = ',
    v: 0,
    t: "sol",
    c: "El carbopol (también denominado carbomer) es un polímero reticulado del ácido acrílico. Se trata de un polímero hidrofílico y, por lo tanto, no repele el agua. En su estructura molecular cuenta con gran cantidad de grupos carboxilo, propiedad que le permite aumentar su volumen en presencia de agua. Al disolverse en el agua, las moléculas de carbopol cambian su configuración e incrementan la viscosidad del líquido, dando lugar a la formación de un gel. Las reacciones de neutralización en medios acuosos permiten que el carbopol se hinche incrementando su volumen, simultáneamente que su viscosidad.",
    };
componente['30']  = {
    n: 'Hipoclorito - ClO− = ',
    v: 0,
    t: "liq",
    c: "Los hipocloritos son sales derivadas del ácido hipocloroso, HClO. Algunos ejemplos frecuentes de hipocloritos son hipoclorito de sodio (lejía de cloro o agente blanqueante) e hipoclorito de calcio (lejía en polvo).​ Los hipocloritos son por lo habitual bastante inestables — por ejemplo, el hipoclorito de sodio no está disponible en forma sólida, ya que al eliminar el agua de una disolución de NaClO, sufre una reacción de dismutación y se convierte en una mezcla de cloruro de sodio y clorato de sodio. El calentamiento de una disolución de NaClO también produce esa reacción. El hipoclorito se descompone bajo la luz solar, dando cloruros y oxígeno.",
    dilu13: "Hipoclorito al 13%",
    };
componente['31']  = {
    n: 'Fosfato Trisodico - Na<sub>3</sub>PO<sub>4</sub> = ',
    v: 0,
    t: "sol",
    c: "Fosfato trisódico (TSP, E339) es un agente de limpieza, aditivo de comidas, removedor de manchas y desengrasante. Es blanco, granular y sólido cristalino, altamente soluble en agua, produciendo una solución alcalina. Para su comercialización, a menudo es hidratado (Na<sub>3</sub>PO<sub>4</sub>·12H2O) y puede variar el rango, hasta su estado anhidro (Na<sub>3</sub>PO<sub>4</sub>).",
    };
componente['32']  = {
    n: 'Carbonato ASH - Na<sub>2</sub>CO<sub>3</sub> = ',
    v: 0,
    t: "sol",
    c: "El carbonato de sodio o carbonato sódico es una sal blanca y translúcida de fórmula química Na<sub>2</sub>CO<sub>3</sub>, usada entre otras cosas en la fabricación de jabón, vidrio y tintes. Es conocido comúnmente como barrilla, natrón, sosa Solvay, soda Solvay, sosa Ash, ceniza de soda y carbonato sódico anhidro​ o simplemente sosa, (no se ha de confundir con la soda cáustica, que es un derivado del carbonato sódico, mediante un proceso conocido como caustificación). Es la sustancia alcalina más común que se conoce y utiliza desde la antigüedad.",
    };
componente['33']  = {
    n: 'Amonio Cuaternario al 80% - NR<sub>4</sub>+ = ',
    v: 0,
    t: "liq",
    c: "Agente antimicrobiano. Se ha demostrado que los cationes cuaternarios de amonio poseen también actividad espermicida y antimicrobiana​ Ciertos compuestos de amonio cuaternario, especialmente aquellos que contienen cadenas alquílicas largas, son empleados como agentes antimicrobianos y desinfectantes.",
    };
componente['34']  = {
    n: 'Formol - CH<sub>2</sub>O = ',
    v: 0,
    t: "liq",
    c: "Las disoluciones acuosas al ~40% se conocen con el nombre de formol, que es un líquido incoloro de olor penetrante y sofocante; estas disoluciones pueden contener alcohol metílico como estabilizante. Puede ser comprimido hasta el estado líquido; su punto de ebullición es -19 °C. ",
    };
componente['35']  = {
    n: 'Acido Sulfonico Lineal = ',
    v: 0,
    t: "liq",
    c: "Es un componente de los detergentes de lavandería y productos de limpieza,  empleado por sus propiedades como surfactante y por ser completamente biodegradable. Es el tensioactivo aniónico más difundido a nivel mundial.",
    };
componente['36']  = {
    n: 'Hidroxido de Sodio o Soda Cautica - NaOH = ',
    v: 0,
    t: "sol",
    c: "El hidróxido de sodio (NaOH), hidróxido sódico o hidrato de sodio, también conocido como soda cáustica o sosa cáustica, es un hidróxido cáustico usado en la industria (principalmente como una base química) en la fabricación de papel, tejidos, y detergentes.",
    };
componente['37']  = {
    n: 'Fenilsulfonato - LAS 50 = ',
    v: 0,
    t: "liq",
    c: "Éste producto es obtenido mediante la sulfonación del alquilbenceno lineal (LAB) con SO3; es un tensoactivo de amplio uso en la fabricación detergentes en polvo, lavaplatos, jabones líquidos, limpiadores para pisos, productos agrícolas, entre otros. Ideal por su rango de color, para la fabricación de jabones líquidos de cualquier tonalidad. Es ampliamente usado en la síntesis de surfactantes del tipo aniónico.", };
componente['38'] = {
    n: 'Comperland K-D = ',
    v: 0,
    t: "liq",
    c: "(Los nombres comerciales de este palabra son: Cocoamida, Genamid). Su nombre científico vizcozante de Betaine este palabra le brinda el 30% de espesor al shampoo.",
};
componente['39']  = {
    n: 'Metil parabeno puro = ',
    v: 0,
    t: "liq",
    c: "(Su nombre comercial propil parabeno puro, su nombre científico Metil Puro). Este producto es un preservante que permite que su aplicacion en productos cosmeticos se preserven por más tiempo.",
};
componente['40']  = {
    n: 'Metil parabeno Sodico = ',
    v: 0,
    t: "liq",
    c: "(Su nombre comercial y científico es el mismo). Este producto combate las bacterias del agua quedando el agua purificada y libre de gérmenes.",
};
componente['41']  = {
    n: 'Ácido Cítrico C<sub>6</sub>H<sub>8</sub>O<sub>7</sub> =',
    v: 0,
    t: "liq",
    c: "Su nombre comercial y científico es el mismo,este producto es sacado de los cítricos es el que regula el Ph o el grado del shampoo.", };
componente['42'] = {
    n: 'Ácido Bórico H<sub>3</sub>BO<sub>3</sub> = ',
    v: 0,
    t: "liq",
    c: "Su nombre comercial y científico es el mismo, actua en el shampoo y en el cuero cabelludo como antibacterial.",
};
componente['43'] = {
    n: 'Cera K.L.E. = ',
    v: 0,
    t: "sol",
    c: "Normalmente esta cera es comercializada con el nombre de Cera en Escamas, y se corresponde a una Cera de Abejas. La cera de abeja es un producto graso producido por las abejas principalmente para construir sus panales. La cera es elaborada por las abejas obreras jóvenes (de 10 a 12 días de edad) mediante 4 pares de glándulas cereras situadas en el abdomen.",
};
componente['44'] = {
    n: 'Trementina de Pino = ',
    v: 0,
    t: "sol",
    c: "La trementina o aguarrás es un líquido incoloro con olor fuerte y usos como desinfectante y disolvente de pintura. Es obtenida a partir del árbol Ocote, del pino y de otras coníferas y terebintáceos. En la actualidad existen varios tipos de trementinas y cada una sirve para cosas diferentes debido a sus propiedades. Así que como hay varias, vamos a ver sus aplicaciones, beneficios medicinales en caso de tenerlos y cómo puedes usar su fórmula química.",
};
componente['45'] = {
    n: 'Detersin - K = ',
    v: 0,
    t: "liq",
    c: "El Detersin es un producto biodegradable, germicida, bactericida utilizado como materia prima indispensable en la fabricación de algunos desinfectantes, detergentes líquidos, detergentes en polvo, desmanchador de pisos de cerámica, desengrasantes industriales, etc.",
};
componente['46'] = {
    n: 'Aceite de Pino = ',
    v: 0,
    t: "liq",
    c: "El aceite de pino es un aceite esencial obtenido por destilación por arrastre de vapor de agujas, ramitas y conos de distintas especies de pino, en especial Pinus sylvestris. Se utiliza en aromaterapia, como un olor en los aceites de baño, como un producto de limpieza, y como lubricante en instrumentos mecánicos.",
};
componente['47'] = {
    n: 'Oxido de Zinc - ZnO = ',
    v: 0,
    t: "sol",
    c: "El óxido de zinc es un compuesto inorgánico con la fórmula ZnO. El ZnO es un polvo blanco insoluble en agua, y es comúnmente usado como aditivo en diversos materiales y productos, como por ejemplo: caucho, plásticos, cerámicas, vidrio, cemento, lubricantes,​ pinturas, ungüentos, adhesivos, selladores, pigmentos,",
};
componente['48'] = {
    n: 'Almidon de Maiz (Maizena) = ',
    v: 0,
    t: "sol",
    c: "La maicena es la fécula o almidón, asimismo llamada en España harina fina de maíz; aunque la definición correcta es harina de fécula de maíz, ya que solo se extrae de esa parte del grano y no del endospermo. También se escribe maizena o maizina, que son marcas vulgarizadas (es decir, marcas que pasaron al uso común). La maicena se utiliza como harina para hacer pan, pastas, bizcochos, bases de pizza, etc. y como espesante para sopas, chocolate caliente, crema pastelera, helados, entre otros. Fue registrada como marca comercial en el año 1856 y adquirida por Corn Products Refining Co. en el año 1900. Maizena se comercializa en todo el mundo y se convirtió en referente del almidón de maíz.",
};
componente['49'] = {
    n: 'Cera de Abeja en Escama = ',
    v: 0,
    t: "sol",
    c: "La cera es el material que las abejas usan para construir sus nidos. Es producida por las abejas melíferas jóvenes que la segregan como liquido a través de sus glándulas cereras. Al contacto con el aire, la cera se endurece y forma pequeñas escamillas de cera en la parte inferior de la abeja.",
};
componente['50'] = {
    n: 'Cera Carnauba = ',
    v: 0,
    t: "sol",
    c: "La cera de carnaúba o carnauba es un tipo de cera que se obtiene de las hojas de la palma Copernicia prunifera. Esta palma es endémica de América del Sur y crece en la región de Ceará, al noreste del Brasil. Para evitar que la palma pierda agua durante la época de sequía, que en la región noreste de Brasil dura hasta seis meses, la planta se cubre de una espesa capa de cera compuesta de ésteres, alcoholes y ácidos grasos de alto peso molecular. Una vez que se cortan las hojas, se secan y trituran para que la cera se desprenda. Esta cera se conoce también como la (Reina de las Ceras), por sus características e infinidad de aplicaciones. La cera de carnaúba es reconocida por sus propiedades de brillo. Combina dureza con resistencia al desgaste. Su punto de fusión es de 78 a 85 °C, el más alto entre las ceras naturales.",
};
componente['51'] = {
    n: 'Vaselina Solida - (C<sub>6</sub>H<sub>10</sub>O<sub>5</sub>)<sub>n</sub> = ',
    v: 0,
    t: "sol",
    c: "La vaselina (del inglés vaseline, un nombre comercial, y éste, quizás, de Wasser —en alemán, 'agua'— + έλαιον —elaion, en griego, 'aceite'— + sufijo -ine)1​2​ es una mezcla homogénea de hidrocarburos saturados de cadena larga, generalmente cadenas de más de 25 átomos de carbono, que se obtienen a partir del refinado de una fracción pesada del petróleo. La composición de dicha mezcla puede variar dependiendo de la clase de chicle y del procedimiento de refinado. Al ser una mezcla presenta un punto de fusión no definido, observándose un reblandecimiento en las proximidades de los 36 °C y completándose el paso al estado líquido sobre los 60 °C. El punto de ebullición está sobre los 350 °C. La vaselina es hidrófoba, es decir, prácticamente no se disuelve en agua, y es menos densa que esta (0,9 g/cm3). Se utiliza como base en muchos productos cosméticos. La marca comercial Vaseline (Vaselina) está registrada en países de habla hispana y portuguesa, sin embargo, utiliza el nombre de Vasenol, ya que «vaselina» se considera un nombre genérico.",
};
componente['52'] = {
    n: 'Acido Fosforico - H<sub>3</sub>PO<sub>4</sub> = ',
    v: 0,
    t: "sol",
    c: "El ácido fosfórico (a veces llamado ácido ortofosfórico) es un compuesto químico ácido (más precisamente un compuesto ternario que pertenece a la categoría de los oxácidos) de fórmula H<sub>3</sub>PO<sub>4</sub>. Es un ortofosfato cuyo código en el Sistema Internacional de Numeración es E-338. No se debe usar agua para eliminar este químico, puesto que esta produce su activación.",
};
componente['53']  = {
    n: 'Harina de Trigo = ',
    v: 0,
    t: "sol",
    c: "Harina de Trigo de uso en la cocina.", };
componente['54'] = {
    n: 'Cera de Candelilla = ',
    v: 0,
    t: "liq",
    c: "La cera proviene de la planta Euphorbia antisyphilitica Zucc o Euphorbia Cerífera también conocida como planta de candelilla, la planta presenta características muy similares a las de un catos, es dura y quebradiza. Sin refinar la cera tiene un aspecto opaco.",
};
componente['55']  = {
    n: 'Aceite de Transmision ATF = ',
    v: 0,
    t: "liq",
    c: "El líquido de transmisión automática (ATF, por sus siglas en inglés) es el líquido que usan los vehículos con cambios automáticos o cajas de transmisión automáticas. Generalmente es de color rojo o verde para distinguirlo del aceite del motor y de otros líquidos del vehículo.", };
componente['56'] = {
    n: 'Genapol en pasta o Pasta Sulfonico = ',
    v: 0,
    t: "sol",
    c: "EL lauril éter sulfato de sodio, o SLES, es un detergente y surfactante encontrado en numerosos productos del cuidado personal (jabón, champú, pasta de dientes). SLES es un económico y muy efectivo agente formador de espuma. SLES, SLS y ALS son surfactantes usados en productos cosméticos por sus propiedades limpiantes y emulsificantes.",
};
componente['57']  = {
    n: 'Talco Neutro - Mg<sub>3</sub>Si<sub>4</sub>O<sub>10</sub>(OH)<sub>2</sub> = ',
    v: 0,
    t: "sol",
    c: "El talco (derivado del Persa: تالک tālk; Árabe: طلق ṭalq) es un mineral de la clase 9 (silicatos), según la clasificación de Strunz, de color blanco a gris azul. En la escala de Mohs se toma como patrón de la menor dureza posible, asignándosele convencionalmente el valor 1. El talco industrial incluye materiales de composición química y mineral no conteniendo perfumes ni aditivos químicos, ya que podría interaccionar con otras sustancias. Es un compuesto inerte no afectado por el ambiente ni degradado.", };
componente['58'] = {
    n: 'Perborato de Sodio - NaBO<sub>3</sub> = ',
    v: 0,
    t: "sol",
    c: "El perborato de sodio es un compuesto químico blanco, inodoro y soluble en agua. Su composición química es NaBO<sub>3</sub>. Se cristaliza como monohidrato, NaBO<sub>3</sub>·H2O, trihidrato, NaBO<sub>3</sub>·3H2O y tetrahidrato, NaBO<sub>3</sub>·4H<sub>2</sub>O.​ El monohidrato y tetrahidrato es la forma en la que se comercializa. La unidad estructural elemental del perborato de sodio es un anión dímero B<sub>2</sub>O<sub>4</sub>(OH)<sub>42</sub>–, en la que dos átomos de boro están unidos por dos puentes de peróxido, de manera que la fórmula simplificada NaBO<sub>3</sub>·nH<sub>2</sub>O es sólo una forma conveniente de expresar la composición química media.",
};
componente['59'] = {
    n: 'Sulfato de Sodio - Na<sub>2</sub>SO<sub>4</sub> = ',
    v: 0,
    t: "sol",
    c: "El tetraoxidosulfato de disodio, sulfato de sodio o antiguamente sulfato sódico (Na<sub>2</sub>SO<sub>4</sub>) es una sustancia incolora, cristalina, con buena solubilidad en el agua y mala solubilidad en la mayoría de los disolventes orgánicos con excepción de la glicerina.",
};
componente['60'] = {
    n: 'Acido Oxalico - H<sub>2</sub>C<sub>2</sub>O<sub>4</sub> = ',
    v: 0,
    t: "sol",
    c: "El ácido oxálico o ácido etanodioico es un ácido dicarboxílico con dos átomos de carbono. Su fórmula molecular es H<sub>2</sub>C<sub>2</sub>O<sub>4</sub> y su fórmula desarrollada HOOC-COOH. Su nombre deriva del género de plantas Oxalis, por su presencia natural en ellas. De hecho, sus sales fueron identificadas en las acederas por el químico y botánico holandés Herman Boerhaave en 1745, siendo aislado por el químico alemán Wiegleb en 1776. Posteriormente se encontró también en una amplia gama de otros vegetales, incluyendo algunos consumidos como alimento como el ruibarbo o las espinacas.",
};
componente['61'] = {
    n: 'Alcanfor - C<sub>10</sub>H<sub>16</sub>O = ',
    v: 0,
    t: "sol",
    c: "El alcanfor es una sustancia semisólida cristalina y cerosa con un fuerte y penetrante olor acre. Es un terpenoide con la fórmula química C<sub>10</sub>H<sub>16</sub>O. Se encuentra en la madera del árbol alcanforero Cinnamomum camphora, un enorme árbol perenne originario de Asia (particularmente de Borneo, de donde toma su nombre alterno Árbol de Borneo), y en algunos otros árboles de la familia de las lauráceas. Puede también ser sintetizado del aceite de trementina. Se usa como bálsamo y con otros propósitos medicinales.",
};
componente['62'] = {
    n: 'Cumarina - C<sub>9</sub>H<sub>6</sub>O<sub>2</sub> = ',
    v: 0,
    t: "sol",
    c: "La cumarina es un compuesto químico orgánico perteneciente a la familia de las benzopironas, cuyo nombre según la IUPAC es 2H-cromen-2-ona. En su estado normal (estándar) se caracteriza por una estructura cristalina e incolora. A este esqueleto se le pueden adicionar diferentes residuos formando la familia de las cumarinas. Las cumarinas se consideran un grupo de metabolitos secundarios de las plantas. Etimológicamente, «cumarina» deriva de la palabra francesa coumarou, utilizada para referirse al haba de Tonka. Se encuentra de forma natural en gran variedad de plantas, y en alta concentración en el Haba de Tonka (Dipteryx odorata), grama de olor (Anthoxanthum odoratum), asperula olorosa (Galium odoratum), gordolobo (Verbascum spp.), hierba de búfalo (Hierochloe odorata), canela de Cassia (Cinnamomum aromaticum), trébol de olor (Melilotus ssp.) y Panicum clandestinum.",
};
componente['63'] = {
    n: 'Anfotero o Probetaina = ',
    v: 0,
    t: "liq",
    c: "En Química, una sustancia anfótera es aquella que puede reaccionar ya sea como un ácido o como una base.1​ La palabra deriva del prefijo griego amphi- (αμφu-) que significa ambos. Muchos metales (tales como zinc, estaño, plomo, aluminio, y berilio) y la mayoría de los metaloides tienen óxidos o hidróxidos anfóteros.",
};
componente['64']  = {
    n: 'Nacarado = ',
    v: 0,
    t: "liq",
    c: "Consiste en un líquido perlado para jabón, champú y gel. Perlante Blanco Líquido para jabón, gel y champú ó Nacarante Líquido. INCI: sodium laureth sulphate, glycol distearate, cocamide mea. Este líquido perlante se usa para dar un color perla o efecto nacarado al champú, gel de ducha, jabón líquido casero o jabón de glicerina.", };
componente['65'] = {
    n: 'Acido Etilendiaminotetraacético EDTA  -  C<sub>10</sub>H<sub>16</sub>N<sub>2</sub>O<sub>8</sub> = ',
    v: 0,
    t: "liq",
    c: "El ácido etilendiaminotetraacético,​ también denominado EDTA o con menor frecuencia AEDT, es una sustancia utilizada como agente quelante que puede crear complejos con un metal que tenga una estructura de coordinación octaédrica.",
};
componente['66'] = {
    n: 'Propilen Glicol - C<sub>3</sub>H<sub>8</sub>O<sub>2</sub> = ',
    v: 0,
    t: "liq",
    c: "El propilenglicol (nombre sistemático: propano-1,2-diol) es un compuesto orgánico (un alcohol, más precisamente un diol) incoloro, insípido e inodoro. Es un líquido aceitoso claro, higroscópico y miscible con agua, acetona, y cloroformo. Se obtiene por la hidratación del óxido de propileno.",
};
componente['67'] = {
    n: 'Alcohol Etilico 96° - C<sub>2</sub>H<sub>6</sub>O = ',
    v: 0,
    t: "liq",
    c: "El alcohol etílico es un líquido incoloro, de olor fuerte e inflamable que se obtiene por destilación de productos de fermentación de sustancias azucaradas o feculentas, como la uva, la melaza, la remolacha o la papa, forma parte de numerosas bebidas (vino, aguardiente, cerveza, etc.) y tiene numerosas aplicaciones industriales.",
};
componente['68'] = {
    n: 'Acetato de Butilo - C<sub>6</sub>H<sub>12</sub>O<sub>2</sub> = ',
    v: 0,
    t: "liq",
    c: "El acetato de butilo es un liquido transparente e incoloro con un olor a fruta. Es miscible con un numero de disolventes organicos; casi insoluble en agua. Acetato de butilo puede ser facilmente hidrolizado en presencia de acidos o soluciones alcalinas.",
};
componente['69'] = {
    n: 'Benzoato de Sodio - C<sub>6</sub>H<sub>5</sub>COONa = ',
    v: 0,
    t: "sol",
    c: "El benzoato de sodio, también conocido como benzoato de sosa o (E211), es una sal del ácido benzoico, blanca, cristalina y gelatinosa o granulada, de fórmula C<sub>6</sub>H<sub>5</sub>COONa. Es soluble en agua y ligeramente soluble en alcohol. La sal es antiséptica y se usa generalmente para conservar los alimentos.",
};
componente['70']  = {
    n: 'NEO PCL O/W  = ',
    v: 0,
    t: "sol",
    c: "Emulsionante O/W básico para cosmética.",
};
componente['71']  = {
    n: 'Extracto de perfume = ',
    v: 0,
    t: "liq",
    c: " ",
};
componente['72'] = {
    n: 'Poli Propilen Glicol fijador PPG 20 Fijador Ax = ',
    v: 0,
    t: "liq",
    c: "El propilenglicol (nombre sistemático: propano-1,2-diol) es un compuesto orgánico (un alcohol, más precisamente un diol) incoloro, insípido e inodoro. Es un líquido aceitoso claro, higroscópico y miscible con agua, acetona, y cloroformo. Se obtiene por la hidratación del óxido de propileno.",
};
componente['73'] = {
    n: 'Soda Cautica liquida 50% - NAOH = ',
    v: 0,
    t: "liq",
    c: "El Hidróxido de Sodio o Soda Cáustica es un líquido de aspecto cristalino y relativamente viscoso que se produce por descomposición electrolítica del cloruro de sodio (sal común). OxyChile produce en Talcahuano soda cáustica líquida grado membrana, que constituye la tecnología más moderna disponible.",
};
componente['74'] = {
    n: 'Urea - CO(NH<sub>2</sub>)<sub>2)</sub> = ',
    v: 0,
    t: "sol",
    c: "La urea es un compuesto químico cristalino e incoloro; Se encuentra abundantemente en la orina y en la materia fecal. Es el principal producto terminal del metabolismo de las proteínas en el humano y en los demás mamíferos.",
};
componente['75'] = {
    n: 'Alcohol Desnaturalizado o Alcohol Fino de Perfumeria  = ',
    v: 0,
    t: "liq",
    c: "El principal producto que se desnaturaliza es el alcohol etílico, llamado entonces alcohol desnaturalizado que se ha hecho tóxico (añadiendo por ejemplo cloruro de benzalconio, metanol, benceno o acetaldehído) y que a veces, por razones de seguridad se tiñe por ejemplo de color azul.",
};
componente['76'] = {
    n: 'Aceite de Almendras Dulces  = ',
    v: 0,
    t: "liq",
    c: "El aceite de almendras se obtiene del fruto seco del almendro, es decir, de la almendra, del cual se extrae su contenido en grasas ya sea, por prensado en frío únicamente con sistemas mecánicos o bien una vez realizado su extracción en frío se somete a refinado.",
};
componente['77']  = {
    n: 'Aceites Escenciales  = ',
    v: 0, t: "liq",
    c: "Son aceites que son obtenidos de plantas, flores, frutos y que por su naturaleza de origen posee fragancias naturales.",
    romero: "Se recomienda el Aceite Escencial de Romero ", };
componente['78']  = {
    n: 'Miristato de Isopropilo - C<sub>17</sub>H<sub>34</sub>O<sub>2</sub> = ',
    v: 0,
    t: "liq",
    c: "El miristato de isopropilo se obtiene por reacción del ácido mirístico o del cloruro de miristoílo con el isopropanol, o bien por esterificación enzimática a baja temperatura. Se trata de un excelente vehículo, ya que es resistente a la oxidación e hidrólisis y no se enrancia.", };
componente['79']  = {
    n: 'Palmitato de Isopropilo - C<sub>19</sub>H<sub>38</sub>O<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "Aplícado en productos en general para el cuidado personal como cremas, emulsiones, lociones, aceites para bebé. Tiene aplicación en productos cosméticos para realzar la suavidad de la piel y dejar una sensación aterciopelada de los productos.",
    };
componente['80'] = {
    n: 'Parafina Liquida  = ',
    v: 0,
    t: "sol",
    c: "Parafina es la denominación general que reciben ciertos sólidos formados a partir de una combinación de hidrocarburos. Estos sólidos no tienen olor y, debido a su menor densidad (0,8 g/cm3), no pueden mezclarse con el agua, aunque sí puede disolverse en éter, etanol caliente, benceno y cloroformo.",
};
componente['81'] = {
    n: 'Cera de Parafina (55°)  = ',
    v: 0,
    t: "sol",
    c: "Parafina es la denominación general que reciben ciertos sólidos formados a partir de una combinación de hidrocarburos. Estos sólidos no tienen olor y, debido a su menor densidad (0,8 g/cm3), no pueden mezclarse con el agua, aunque sí puede disolverse en éter, etanol caliente, benceno y cloroformo.",
};
componente['82'] = {
    n: 'Aceite Mineral Emulsionado  = ',
    v: 0,
    t: "liq",
    c: "Una emulsión de aceite mineral se crea mediante la combinación de aceite mineral con un polvo hecho del árbol de acacia. El aceite mineral usado en la emulsión es un producto a base de petróleo que ha sido refinada a un estado que puede ser ingerida con seguridad.",
};
componente['83']  = {
    n: 'Polisorbato-80 (Polioxietilen-80-sorbitan-monooleato HLB=15)  = ',
    v: 0,
    t: "liq",
    c: "El monooleato de polioxietilen(20)sorbitano, o polisorbato 80 es un aditivo alimentario con acción detergente: Emulsiona y disuelve las grasas. Es una sustancia aprobada por la Unión Europea, para uso en Alimentos e identificada como emulsionante, E-433. Es un líquido viscoso de color amarillo soluble en agua.",
    };
componente['84'] = {
    n: 'Brij-92 (Polioxietilen-oleil-eter HLB=4,9)  = ',
    v: 0,
    t: "liq",
    c: "Los alcoholes grasos etoxilados o éteres de poliglicol de alcoholes grasos o polialquilenglicoléteres, con una estrecha distribución de homólogos, también conocidos como etoxilados de rango estrecho, son tensioactivos no iónicos conocidos, que se producen industrialmente, por ejemplo, por adición de óxido de etileno. ",
};
componente['85'] = {
    n: 'CETIOL HE (Polietilenglicol-7-gliceril-cocoato) Tambien: (2-octil-dodecanol)  = ',
    v: 0,
    t: "liq",
    c: "Dodecanol se utiliza para fabricar tensoactivos, aceites lubricantes, productos farmacéuticos, en la formación de polímeros monolíticos y como mejorador del sabor aditivo alimentario. En cosmética, dodecanol se utiliza como un emoliente.",
};
componente['86'] = {
    n: 'Miristol 318 (Triglicerido de Ac. Caprilico o Caprinico )  = ',
    v: 0,
    t: "liq",
    c: "El ácido caprílico es un ácido graso que se encuentra naturalmente en el coco y la leche materna. Este ácido graso saturado, también conocido como ácido octanoico, también está presente en la mantequilla y el aceite de palma. ",
};
componente['87'] = {
    n: 'Aceite de Germen de Trigo  = ',
    v: 0,
    t: "liq",
    c: "Este aceite es obtenido a partir del Germen del Trigo. Es muy rico en Vitamina E, de hecho se considera la fuente más importante de vitamina E (entre 300-450 mg./100 g. Contiene también minerales, carbohidratos y proteínas de fácil asimilación. Contiene pequeñas cantidades de ácidos grasos tipo omega 3, omega 6.",
};
componente['88'] = {
    n: 'Aceite de Parafina  = ',
    v: 0,
    t: "liq",
    c: "El aceite de parafina líquida es una forma de aceite que ha sido obtenida de varias fuentes, incluyendo ballenas, carbón y aceite crudo. En la era moderna, el proceso de destilar aceite crudo produce esta sustancia rica en hidrocarbón como un subproducto. Tiene muchos usos prácticos para la medicina.",
};
componente['89'] = {
    n: 'Manteca de Cacao  = ',
    v: 0,
    t: "sol",
    c: "La manteca de cacao, también llamada aceite de theobroma, es la grasa natural comestible procedente del haba del cacao, extraída durante el proceso de fabricación del chocolate y que se separa de la masa de cacao mediante presión. La manteca de cacao tiene un suave aroma y sabor a chocolate.",
};
componente['90'] = {
    n: 'Butilhidroxianisol (BHA)  = ',
    v: 0,
    t: "sol",
    c: "El hidroxibutilanisol (BHA por sus siglas en inglés) es una mezcla de dos isómeros de compuestos orgánicos, 2-tert-butil-4-hidroxianisol y 3-tert-butil-4-hidroxianisol. Se prepara a partir de 4-metoxifenol e isobutileno. Es un sólido ceroso que exhibe propiedades antioxidantes. Del mismo modo que el hidroxibutiltolueno(BHT), el anillo aromático conjugado del BHA es capaz de estabilizar a un radical libre, secuestrándolo.Al actuar como un agente secuestrante, se evitan posteriores reacciones de radicales libres.",
};
componente['91']  = {
    n: 'Pigmento  = ',
    v: 0,
    t: "liq",
    c: "Normalmente se refiere a colorante en base grasa.",
    color: " Pigmento De Cualquier Color = ",
    blanco: " Pigmento de Color Blanco = ", };
componente['92'] = {
    n: 'Aceite de Lanonina  = ',
    v: 0,
    t: "liq",
    c: "La lanolina es una cera natural producida por las glándulas sebáceas de algunos mamíferos, especialmente del ganado ovino, preparada y que se aplica para diversos usos industriales, farmacéuticos y domésticos.",
};
componente['93'] = {
    n: 'Dioxido de Titanio - TiO<sub>2</sub>  = ',
    v: 0,
    t: "sol",
    c: "El dióxido de titanio (TiO2) es una de las substancias químicas más blancas que existen: refleja prácticamente toda la radiación visible que le llega y mantiene su color pase lo que pase cuando otros compuestos se decoloran con la luz.",
};
componente['94'] = {
    n: 'Calamina (Carbonato de Zinc ZnCO<sub>3</sub>)  = ',
    v: 0,
    t: "sol",
    c: "El zinc o cinc es el elemento químico de número atómico 30, cuyo símbolo es Zn. Se trata de un metal abundante en la corteza terrestre, de color blanco y brillante, que puede aparecer en forma de silicato, sulfuro o carbonato.",
};
componente['95'] = {
    n: 'Tween 20 - Polisorbato 20 - C<sub>58</sub>H<sub>114</sub>O<sub>26</sub> = ',
    v: 0,
    t: "liq",
    c: "El polisorbato 20 o monolaurato de polioxietilen(20)sorbitano, conocido comercialmente como Tween 20, es un tensoactivo tipo polisorbato cuya estabilidad y relativa ausencia de toxicidad permiten que sea usado como detergente y emulsionante en numerosas aplicaciones domésticas, científicas, alimentarias, industriales y farmacológicas. Es un aditivo alimentario aprobado por la Unión Europea, para uso en Alimentos e identificada como E 432.",
};
componente['96'] = {
    n: 'Extracto de Agua de Rosas  = ',
    v: 0,
    t: "liq",
    c: "El agua de rosas, sirope de rosas es una porción de un destilado de un hidrosol (destilado de hierbas) procedente de los pétalos de las rosas. Se emplea en la producción de aceite de rosas para su uso en la elaboración de perfumes, como saborizante de ciertos alimentos y como un componente cosmético en preparaciones médicas, en este aspecto podemos destacar su poder hidratante para la piel. También se utiliza en rituales religiosos en Europa y Asia.",
};
componente['97'] = {
    n: 'Clorhidrato de Aluminio  = ',
    v: 0,
    t: "sol",
    c: "El clorhidrato de aluminio es el ingrediente químico más presente en los desodorantes comerciales. Es casi imposible encontrar uno que no contenga derivados del aluminio en perfumerías o supermercados, a no ser alguna gama especial de ciertas marcas comerciales.",
};
componente['98'] = {
    n: 'Alumbre  = ',
    v: 0,
    t: "sol",
    c: "Se conoce como alumbre a un tipo de sulfato triple compuesto por el sulfato de un metal trivalente, como el aluminio, y otro de un metal monovalente. También se pueden crear dos soluciones: una solución saturada en caliente y una solución saturada en frío. Generalmente se refiere al alumbre potásico KAl(SO<sub>4</sub>)<sub>2</sub>·12H<sub>2</sub>O (o a su equivalente natural, la calinita). Una característica destacable de los alumbres es que son equimoleculares, porque por cada molécula de sulfato de aluminio hay una molécula de sulfato del otro metal, y cristalizan hidratados con 12 moléculas de agua en un sistema cúbico.Se utiliza en las valoraciones argentométricas, específicamente en el método de Volhard(véase Jacob Volhard) para la determinación de haluros como cloruros.",
};
componente['99'] = {
    n: 'Triclosán  = ',
    v: 0,
    t: "sol",
    c: "El triclosán es un potente agente antibacteriano y fungicida. En condiciones normales se trata de un sólido incoloro con un ligero olor a fenol. En caso de ser ingerido, puede llegar a causar enfermedades graves e incluso la muerte, dependiendo la cantidad de la misma que entre a la boca.",
};
componente['100'] = {
    n: 'Acido Estearico - CH<sub>3</sub>(CH<sub>2</sub>)16COOH  = ',
    v: 0,
    t: "sol",
    c: "El ácido esteárico es un ácido graso saturado de 18 átomos de carbono presente en aceites y grasas animales y vegetales. A temperatura ambiente es un sólido parecido a la cera; su fórmula química es CH<sub>3</sub>(CH<sub>2</sub>)16COOH.Su nombre IUPAC es ácido octadecanoico.Tiene una cadena hidrofóbica de carbono e hidrógeno. Se obtiene tratando la grasa animal con agua a una alta presión y temperatura, y mediante la hidrogenación de los aceites vegetales.Algunas de sus sales, principalmente de sodio y potasio, tienen propiedades como tensoactivas.Es muy usado en la fabricación de velas, jabones y cosméticos.",
};
componente['101'] = {
    n: 'Extracto de Malva (Glicolico de Malva)  = ',
    v: 0,
    t: "liq",
    c: "La malva común (Malva sylvestris) es una planta herbácea de la familia de las malváceas, muy abundante en toda Europa en bordes de caminos y en terrenos tanto cultivados como no cultivados.",
};
componente['102'] = {
    n: 'Bromoclorofenol  = ',
    v: 0,
    t: "liq",
    c: "El azul de bromofenol es un compuesto orgánico usado en análisis de laboratorio como indicador de pH. También se le conoce como 3,3,5,5 -tetrabromofenol sulfonftaleína o azul de tetrabromofenol.", };
componente['103'] = {
    n: 'PEG-7-GLYCERYL-COCOATO  = ',
    v: 0,
    t: "liq",
    c: "Es un polímero sintético se basa en PEG (polietilenglicol) y ácidos grasos derivados del aceite de coco. Debido a la presencia de PEG, este ingrediente puede contener impurezas de fabricación potencialmente tóxicas, como 1,4-dioxano.", };
componente['104'] = {
    n: 'Liposoma A/E  = ',
    v: 0,
    t: "sol",
    c: "Los liposomas son ingredientes que muchas cremas y tratamientos de belleza contienen para que los activos puedan ser absorbidos de manera eficaz… básicamente para que puedan ingresar directamente al interior de la célula y esta utilizarlos. Los liposomas son vesículas esféricas con una membrana de fosfolípidos(hidrosoluble y liposoluble) de esta manera puede atravesar fácilmente la membrana celular la cual está conformada en parte también por fosfolípidos. Los cosméticos que los contienen cuentan con una efectividad mucho más elevada porque de esta manera se asegura de que componentes como por ejemplo coenzima Q10, vitaminas, retinol o ácido hialurónico lleguen al interior de la célula para ser utilizados para su buen funcionamiento, reparación o desintoxicación. ", };
componente['105'] = {
    n: 'Extracto Fluido de Hiedra  = ',
    v: 0,
    t: "liq",
    c: "Hiedra es una planta trepadora de hojas perennes que ha sido ampliamente utilizada con fines medicinales. Hay que distinguirla de la hiedra venenosa que se encuentra en América. La hiedra es una planta relicta y uno de los escasos sobrevivientes en Europa de la flora laurisilva de la era terciaria. Se cree que su fácil dispersión por las aves la ayudó a colonizar de nuevo amplias zonas de donde había desaparecido durante las glaciaciones.", };
componente['106'] = {
    n: 'Extracto Fluido de Fucus  = ',
    v: 0,
    t: "liq",
    c: "Fucus es un género de algas pardas (clase Phaeophyceae) que se encuentra en las zonas intermareales de las costas rocosas. Es un género muy común en las costas atlánticas de Europa y Norteamérica.", };
componente['107'] = {
    n: 'Extracto Fluido de Castaño  = ',
    v: 0,
    t: "liq",
    c: "Aesculus hippocastanum, el castaño de Indias,​ es un árbol de gran porte perteneciente a la familia de las sapindáceas. Se denomina comúnmente falso castaño debido a que sus frutos presentan una gran similitud externa con los de los árboles del género Castanea, de la familia de las fagáceas.", };
componente['108'] = {
    n: 'Extracto Fluido de Ruscus  = ',
    v: 0,
    t: "liq",
    c: "Ruscus es un género con seis especies perteneciente a la familia Asparagaceae, anteriormente Ruscaceae, la que hasta hace pocos años era ubicada dentro de Liliaceae ampliamente definida. El género es nativo del oeste y sur de Europa (Inglaterra), Macaronesia, noroeste de África, y suroeste de Asia al este del Cáucaso.", };
componente['109'] = {
    n: 'Cafeina  = ', v: 0, t: "liq", c: "La cafeína es un alcaloide del grupo de las xantinas, sólido cristalino, blanco y de sabor amargo, que actúa como una droga psicoactiva, levemente disociativa y estimulante por su acción antagonista no selectiva de los receptores de adenosina. La cafeína recibe también otros nombres (guaranina, teína, mateína) relativos a las plantas de donde se puede extraer y porque contiene otras sustancias que aparecen en esos casos. La denominada guaranina del guaraná, y la teína del té, son en realidad la misma molécula de cafeína, hecho que se ha confirmado en análisis de laboratorio. Estas plantas contienen algunos alcaloides adicionales como los estimulantes cardíacos teofilina y teobromina y a menudo otros compuestos químicos como polifenoles, que pueden formar complejos insolubles con la cafeína.", };
componente['110'] = {
    n: 'Aceite de Aguacate  = ',
    v: 0,
    t: "liq",
    c: "El aguacate o palta es una fruta con una carne verde vivo, muy rica y nutritiva. Nos aporta fóforo, potasio, hierro, magnesio, betacarotenos, cobre y vitaminas como las del grupo B (incluyendo ácido fólico), E y K. De esta fruta se extrae un aceite muy valorado por sus propiedades nutricionales y sus beneficios para la salud. Te aconsejo que busques siempre un aceite que provenga de aguacates o paltas ecológicos y que esté prensado en frío, así te aseguras la máxima calidad. ", };
componente['111'] = {
    n: 'Propil Parabeno (Propil Parahidroxibenzoato) - C<sub>10</sub>H<sub>12</sub>O<sub>3</sub> = ',
    v: 0,
    t: "sol",
    c: "El Propil parahidroxibenzoato es un éster propílico del ácido parahidroxibenzoico. Se trata de una substancia natural que se encuentra en las plantas. Es empleado por la industria alimentaria como un conservante alimentario de código: E 216. Se emplean también en productos de cosmética como pueden ser lociones, cremas y demás. ", };
componente['112'] = {
    n: 'Mentol - C<sub>10</sub>H<sub>20</sub>O = ',
    v: 0,
    t: "sol",
    c: "Sustancia en forma de cristales prismáticos que se obtiene de la esencia de menta y se emplea principalmente como antiséptico y en la elaboración de perfumes y licores. El mentol es un alcohol secundario saturado, que se encuentra en los aceites de algunas especies de menta, principalmente en Mentha arvensis y menta piperita (Mentha x piperita);2​ es un sólido cristalino que funde alrededor de los 40 °C (104 °F) y que se emplea en medicina y en algunos cigarrillos porque posee un efecto refrescante sobre las mucosas. Tiene también propiedades antipruriginosas y antisépticas. Es insoluble en agua y soluble en alcohol y éter.", };
componente['113'] = {
    n: 'Eucaliptol (Aceite de Eucalipto) - C<sub>10</sub>H<sub>18</sub>O  = ', v: 0, t: "liq", c: "Tambien llamado aceite de eucalipto se obtiene de las hojas de las diversas especies de eucalipto. Su principal compuesto es el eucaliptol (1,8-cineol). Se trata de un líquido miscible con alcohol, su olor varía entre el de la menta y el de la trementina.Se utiliza en perfumería, medicina y para la flotación de minerales.Se encuentra en muchos productos, ungüentos y linimentos, cremas para la pañalitis(dermatitis del pañal de los bebés), inhaladores para aliviar la congestión nasal, medicamentos para el dolor en encías, boca y garganta y enjuagues bucales. ", };
componente['114'] = {
    n: 'Extracto Glicolico de Centella Asiatica  = ',
    v: 0,
    t: "sol",
    c: "Centella asiatica es una pequeña planta anual herbácea de la familia Apiaceae, de Asia. Nombres comunes incluyen: Gotu Kola, Antanan, Pegaga, Brahmi y Centella Asiática.",
};
componente['115'] = {
    n: 'Colofonia  = ',
    v: 0,
    t: "sol",
    c: "Resina sólida, translúcida, pardusca o amarillenta, e inflamable, que se obtiene de la destilación de la trementina del pino y se emplea en cosmética, farmacia, etc.", };
componente['116'] = {
    n: 'Cera Microcristalina  = ',
    v: 0,
    t: "sol",
    c: "Las Ceras microcristalinas son un tipo de ceras producido por el desengrase del petróleo, como parte del proceso de refinamiento del petróleo. En contraste con la cera de parafina, que contiene sobre todo alcanos no ramificados, la cera microcristalina contiene un porcentaje más alto de hidrocarburos ", };
componente['117'] = {
    n: 'Vaselina Liquida  = ',
    v: 0,
    t: "liq",
    c: "Es u tipo de aceite derivado del petróleo (mezcla de líquidos grasos que se encuentran en la tierra). El aceite de vaselina se usa en laxantes, lubricantes, cremas y lociones.",
 };
componente['118'] = {
    n: 'Alfa Tocoferol - C<sub>29</sub>H<sub>50</sub>O<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "El tocoferol es el nombre de varios compuestos orgánicos conformados por varios fenoles metilados, que forman una clase de compuestos químicos llamados tocoferoles de los cuales varios actúan como Vitamina E. Debido a su actividad de vitamina, el primer tocoferol fue identificado por primera vez en 1936 a partir de la actividad de un factor dietético de fertilidad en ratas, y se le llamó así por la combinación de las palabras griegas",
 };
componente['119'] = {
    n: 'Crema Base  = ',
    v: 0,
    t: "sol",
    c: "La crema base hidratante es una crema semi-elaborada libre de parabenos, siliconas y aceites minerales. A esta crema se le puede añadir hasta un 6% de activos como aceites vegetales, aceites esenciales, principios activos, perfumes, etc. Todo para hacer una fantástica línea de cremas únicas y originales hechas por ti.",
 };
componente['120'] = {
    n: 'Aceite de Rosa Mosqueta  = ',
    v: 0,
    t: "liq",
    c: "El aceite de rosa mosqueta es un producto natural verdaderamente extraordinario con sorprendentes propiedades de rejuvenecimiento de la piel y acondicionamiento del cabello. Se ha utilizado durante generaciones por los indios de los Andes chilenos quienes reconocieron hace años que el aceite de rosa mosqueta tenía excepcionales beneficios para la curación de la piel de curación y el cuidado del cabello. Está lleno de nutrientes y vitaminas naturales y se puede utilizar tanto externa como internamente tomado.",
 };
componente['121']  = {
    n: 'Hidroxido de Sodio o Soda Cautica al 40% - NaOH = ',
    v: 0,
    t: "sol",
    c: "El hidróxido sódico, o hidróxido de sodio, es una sustancia generalmente conocida como sosa cáustica. Es una sustancia química compuesta por sodio, hidrógeno y oxígeno altamente corrosiva cuya fórmula es NaOH",
};
componente['122'] = {
    n: 'Extracto Glicolico de Equisetum  = ',
    v: 0,
    t: "sol",
    c: "El Equisetum arvense o cola de caballo es una especie de arbusto perteneciente a la familia de las equisetáceas.",
};
componente['123'] = {
    n: 'Katon CG ® al 0,05%  = ',
    v: 0,
    t: "liq",
    c: "Por su condición de biocida, el Katon CG se utiliza preferentemente en la industria cosmética, para la conservación de productos cosméticos tales como champúes, geles, cremas para la piel y lociones. Ataca bacterias Gram+ y Gram-",
};
componente['124'] = {
    n: 'Monoestearato de Glicerol - C<sub>21</sub>H<sub>42</sub>O<sub>4</sub>  = ',
    v: 0,
    t: "sol",
    c: "El monoestearato de glicerol, o gliceril monoestearato, es un compuesto orgánico que ocurre de forma natural en el metabolismo de las grasas. Se utiliza como aditivo emulgente (E471) en la industria alimentaria, farmacéutica y cosmética.",
};
componente['125'] = {
    n: 'Emulgin B-1  = ',
    v: 0,
    t: "sol",
    c: "Eumulgin® B 1 es y se puede aplicar universalmente como un emulsionante no iónico para emulsiones cosméticas de aceite en agua. Se trata de una masa blanca, similar a la cera, con un olor suave. Este producto tiene un valor de hidroxilo de 70-75, un contenido de agua de <= 1,0%, un punto de 34-37°C saponificación, y una temperatura de nube de 90-97°C.",
};
componente['126'] = {
    n: 'Acido Tioglicolico al (80%) - C<sub>2</sub>H<sub>4</sub>O<sub>2</sub>S  = ',
    v: 0,
    t: "sol",
    c: "Pertenece a la familia de los Alfa Hidróxido Ácido (AHA), un grupo de ácidos presente en varios productos naturales que han sido sintetizados en laboratorios para su uso comercial. El ácido glicólico en particular fue descubierto en los años 80 por los doctores Eugene Van Scott y Ruey Yu, se extrae de la caña de azúcar y es altamente soluble en agua.",
};
componente['127'] = {
    n: 'Bentonita  = ',
    v: 0,
    t: "sol",
    c: "La bentonita es una arcilla de grano muy fino (coloidal) del tipo de montmorillonita que contiene bases y hierro. Tiene aplicaciones en cerámica, entre otros usos. El nombre deriva de un yacimiento que se encuentra en Fort Benton, Estados Unidos.",
};
componente['128'] = {
    n: 'Extracto de Hiperico  = ',
    v: 0,
    t: "liq",
    c: "Hypericum perforatum, también conocida como hipérico, hipericón, corazoncillo o hierba de San Juan , es la especie más abundante de la familia de las gutíferas (Guttiferae) o hipericáceas (Hypericaceae). Hypericum perforatum es una planta medicinal con múltiples aplicaciones. Por ejemplo, su aplicación tópica sirve para acelerar la cicatrización de las heridas.",
};
componente['129'] = {
    n: 'Extracto de Manzanilla  = ',
    v: 0,
    t: "liq",
    c: "El extracto de manzanilla o camomila tiene propiedades antisépticas, cicatrizantes y aclaradoras del cabello. Por lo que se puede utilizar en forma de crema o pomada para desinfectar la piel, geles cicatrizantes o champús que aclaren el pelo y le den brillo y luminosidad.",
};
componente['130'] = {
    n: 'Aceite de Calendula  = ',
    v: 0,
    t: "liq",
    c: "Las principales propiedades del aceite de caléndula son antiinflamatorias, fungicidas, antisépticas, vulnerarias, hidratantes y tónicas de la piel.",
};
componente['131'] = {
    n: 'Sorbitol a (70%) - C<sub>6</sub>H<sub>14</sub>O<sub>6</sub>  = ',
    v: 0,
    t: "liq",
    c: "El sorbitol es un polialcohol o alcohol polihidrico de azúcar descubierto por el francés Boussingault en 1872 en las bayas de Sorbus aucuparia L. (comúnmente llamado serbal de cazadores). En la naturaleza el sorbitol es uno de los tres glúcidos (sacarosa, almidón y sorbitol) principales producidos por la fotosíntesis en las hojas adultas de ciertas plantas de las familias Rosaceae y Plantaginaceae. Se encuentra en cantidades apreciables en las algas rojas y, junto a la fructosa, la glucosa y la sacarosa, en frutos como las peras, las manzanas, las cerezas y los melocotones o duraznos.",
};
componente['132'] = {
    n: 'Almidon de Arroz o Harina de Arroz  = ',
    v: 0,
    t: "sol",
    c: "En el baño, el maquillaje, para calmar el enrojecimiento e incluso para eliminar los olores. Estos, y muchos más, son los usos del almidón de arroz en la cosmética natural. El almidón de arroz es un polvo blanco muy fino que se obtiene de la transformación de granos de arroz.",
};
componente['133'] = {
    n: 'Unguento Emulsificante No Ionico o Cetomacrogol 1000  = ',
    v: 0,
    t: "liq",
    c: "Cetomacrogol 1000 es el nombre comercial para polietilenglicol hexadecil éter, que es un tensioactivo no iónico producido por la etoxilación de alcohol cetílico para dar material con la fórmula general HO (C2H4O) nC16H33. Varias calidades de este material están disponibles dependiendo del nivel de etoxilación realizado, con unidades repetitivas (n) de polietilenglicol que varían entre 2 y 20. Si n = 20 se denomina Brij58. Si n = 10, se llama Brij56. Se utiliza como agente solubilizante y emulsionante en alimentos, cosméticos y productos farmacéuticos, a menudo como una base subyacente, y también como una herramienta de investigación. Se usa como emulsionante O / W para cremas / lociones; Agente humectante en palos; Cumple con las especificaciones de BP. Use 0.5-5%.",
};
componente['134'] = {
    n: 'Vitamina F u Omega 3  = ',
    v: 0,
    t: "liq",
    c: "La Vitamina F - Ester Glicérico es una mezcla de ácidos grasos poliinsaturados, esterificados y biológicamente activos. Los ácidos grasos poliinsaturados constituyentes de la vitamina F-Ester glicérico no pueden ser sintetizados por el organismo y por ello es necesario su aporte externo.",
};
componente['135'] = {
    n: 'Neo-Heliopan B-B oxibenzona - C<sub>14</sub>H<sub>12</sub>O<sub>3</sub>  = ',
    v: 0,
    t: "sol",
    c: "Tambien conocido como Oxibenzona 2-hidroxi-4-metoxibenzofenona el Heliopan® BB es un absorbente eficaz de amplio espectro soluble en aceite con un máx. protección en los espectros UVB y UVA de onda corta (UVB a aproximadamente 286 nm, UVA a aproximadamente 325 nm).",
};
componente['136'] = {
    n: 'Neo-Heliopan - C<sub>14</sub>H<sub>12</sub>O<sub>3</sub>  = ',
    v: 0,
    t: "sol",
    c: "Tambien conocido como Oxibenzona 2-hidroxi-4-metoxibenzofenona el Neo Heliopan® AV es un absorbente de UVB altamente efectivo con una extinción específica(E 1 % / 1cm) de mín. 830 a 308 nm en Metanol y tiene absorción adicional en el espectro de UVA de onda corta. El absorbente de UVB es soluble en aceite y prácticamente inodoro, adecuado para una amplia variedad de aplicaciones cosméticas. Sigue siendo un líquido a temperaturas tan bajas como -10 ° C. y puede aumentar el SPF cuando se usa en combinación con otros filtros UV.",
};
componente['137'] = {
    n: 'Vitamina E Acetato  = ',
    v: 0,
    t: "sol",
    c: "El acetato de vitamina E, también conocido como acetato de tocoferol, es una forma seca de la vitamina E que se conoce comúnmente como tocoferoles. La forma de acetato es un éster que tiene una estabilidad mayor que los tocoferoles no esterificados.",
};
componente['138'] = {
    n: 'Nipagin - C<sub>8</sub>H<sub>8</sub>O<sub>3</sub>  = ',
    v: 0,
    t: "sol",
    c: "Sinónimos: Parahidroxibenzoato de metilo. Metilparaben. E-218. INCI: Methylparaben. Polvo cristalino blanco o casi blanco, o cristales incoloros.Muy poco soluble en agua y fácilmente soluble en etanol y en metanol.Punto de fusión: 131 ºC.",
};
componente['139'] = {
    n: 'Nipasol - C<sub>8</sub>H<sub>12</sub>O<sub>3</sub> = ',
    v: 0,
    t: "sol",
    c: "Sinónimos: Parahidroxibenzoato de propilo. Propilparaben. E-216. INCI: Propylparaben. Polvo cristalino blanco o casi blanco.Muy poco soluble en agua, fácilmente soluble en etanol al 96 % y en metanol. Punto de fusión: 96– 97 ºC.",
};
componente['140'] = {
    n: 'Vitamina C Acido L-ascorbico - C<sub>6</sub>H<sub>8</sub>O<sub>6</sub>  = ',
    v: 0,
    t: "sol",
    c: "La vitamina C, enantiómero L del ácido ascórbico o antiescorbútica, es un nutriente esencial para el ser humano, los primates, los cobayos y algunos murciélagos, quienes carecen del mecanismo para su síntesis.",
};
componente['141'] = {
    n: 'Dowicil - C<sub>9</sub>H<sub>16</sub>C<sub>l2</sub>N<sub>4</sub>  = ',
    v: 0,
    t: "sol",
    c: "Agente antimicrobiano bactericida, activo frente a bacterias, levaduras, y mohos a bajas concentraciones.Es particularmente activo frente a Pseudomonas. Se utiliza como conservante para uso farmacéutico y cosmético. Se suele incorporar a las formulaciones disuelto en una pequeña cantidad de agua o de alcohol 96%, y siempre en frío, ya que se descompone a 60 ºC.",
};
componente['142'] = {
    n: 'Elastina  = ',
    v: 0,
    t: "liq",
    c: "La elastina es una proteína con funciones estructurales que confiere elasticidad a los tejidos; las fibras de elastina sujetan las fibras de colágeno, manteniéndolas en su lugar. Aunque la elastina no es tan abundante como el colágeno en la piel, desempeña un papel determinante a la hora de mantener la piel joven pero estas fibras se van debilitando con el paso de los años.",
};
componente['143'] = {
    n: 'Colageno Hidrosoluble  = ',
    v: 0,
    t: "sol",
    c: "El colágeno hidrolizado se obtiene mediante hidrólisis enzimática, a partir del Colágeno tipo I (el que forma la piel y los huesos).Su apariencia es la de un polvo blanco altamente soluble e inodoro Se caracteriza por la abundancia de aminoácidos específicos que no entran en la composición de otras proteínas.",
};
componente['144'] = {
    n: 'Hidroviton  = ',
    v: 0,
    t: "liq",
    c: "Hidrovitón es un complejo hidratante con una actividad altamente nutritiva",
};
componente['145'] = {
    n: 'Extracto Glicolico de Castaño Indias  = ',
    v: 0,
    t: "sol",
    c: "En el castaño de indias, su semilla, corteza, flores y hojas se utilizan para hacer las medicinas. Las semillas y la hoja se utilizan para el tratamiento de las varices, las hemorroides y las venas hinchadas (flebitis). La hoja del castaño de Indias se utiliza para el eczema, los dolores menstruales, la hinchazón de los tejidos",
};
componente['146'] = {
    n: 'Extracto Glicolico de Corallina officinalis  = ',
    v: 0,
    t: "sol",
    c: "Corallina officinalis es una especie de alga roja calcárea que crece en las zonas bajas y medias del litoral en las costas rocosas . Se encuentra principalmente en crecimiento alrededor de los bordes de las pozas de marea, pero se puede encontrar en las grietas superficiales en cualquier lugar de la costa rocosa",
};
componente['147'] = {
    n: 'Resina Nitrocelulosa  = ',
    v: 0,
    t: "sol",
    c: "La nitrocelulosa fue la primera resina semi-sintética estable, que se usó para el recubrimiento y acabado de la madera, debido a su textura rígida y resistente al impacto. Si al nitrato de celulosa, lo mezclamos con solventes y plastificantes, mejoramos sus condiciones de flexibilidad y secado más rápido.",
};
componente['148'] = {
    n: 'Disolvente Tiner Acrilico  = ',
    v: 0,
    t: "liq",
    c: "El diluyente (thinner en inglés), también conocido como adelgazador o rebajador de pinturas, es una mezcla de disolventes de naturaleza orgánica derivados del petróleo que ha sido diseñado para disolver, diluir sustancias insolubles en agua, como la pintura de esmalte o basada en aceites, los aceites y las grasas.",
};
componente['149'] = {
    n: 'Cera Licowax  = ',
    v: 0,
    t: "liq",
    c: "Contiene esteres montanicos que permiten la elaboración de emulsiones autobrillantes, que ya contiene emilsionante, uso para ceras emulsionadas de alto brillo, resistencia al tráfico, ceras para estuco veneciano.",
};
componente['150'] = {
    n: 'Resina Alquidica  = ',
    v: 0,
    t: "liq",
    c: "Una resina alquídica es básicamente un poliéster cuya cadena principal está modificada con moléculas de ácido graso, las que le otorgan propiedades particulares.",
};
componente['151'] = {
    n: 'Cromato de Zinc - (ZnCrO<sub>4</sub>)  = ',
    v: 0,
    t: "liq",
    c: "Es un compuesto químico inorgánico primario utilizado en la industria y en la fabricación de pinturas como anticorrosivo elaborado a base de resina vinil alquidal y óxido de hierro. Este producto también tiene una elevada toxicidad y está considerado como potencial carcinógeno y mutagénico.",
};
componente['152'] = {
    n: 'Naftenato de Cobalto  = ',
    v: 0,
    t: "sol",
    c: "Acelerante o catalizador del fraguado de plásticos reforzados, también de las reacciones de los isocianatos (colas instantáneas)",
};
componente['153'] = {
    n: 'Oxido de Hierro - Fe(OH)<sub>n</sub>  = ',
    v: 0,
    t: "sol",
    c: "Los óxidos de hierro son compuestos químicos formados por hierro y oxígeno. Algunos de estos óxidos son utilizados en cerámica, particularmente en vidriados. Los óxidos de hierro, como los óxidos de otros metales, proveen el color de algunos vidrios después de ser calentados a altas temperaturas. También son usados como pigmento.",
};
componente['154'] = {
    n: 'Piedra Pomes en Polvo  = ',
    v: 0,
    t: "sol",
    c: "La pumita (también llamada piedra pómez, jal o liparita) es una roca ígnea volcánica vítrea, con baja densidad —flota en el agua— y muy porosa, de color blanco o gris. Cuando se refiere a la piedra pómez en lo que respecta a sus posibles aplicaciones industriales, también puede ser conocida como puzolana. En su formación, la lava proyectada al aire sufre una gran descompresión. Como consecuencia de la misma se produce una desgasificación quedando espacios vacíos separados por delgadas paredes de vidrio volcánico.",
};
componente['155'] = {
    n: 'Carbonato de Calcio - CaCO<sub>3</sub>  = ',
    v: 0,
    t: "liq",
    c: "Se trata de un compuesto ternario, que entra en la categoría de las oxosales. En medicina se utiliza habitualmente como suplemento de calcio, como antiácido y agente absorbente. Es fundamental en la producción de vidrio y cemento, entre otros productos.",
};
componente['156'] = {
    n: 'Emulgade - F  = ',
    v: 0,
    t: "liq",
    c: "Es una base auto-emulsionante aniónica con propiedades de consistencia, es adecuada para la preparación de cosméticos, cremas y lociones.",
};
componente['157'] = {
    n: 'Octildodecanol  = ',
    v: 0,
    t: "liq",
    c: "Se trata de un emoliente cuyo medio de propagación es debido a su estructura química estable a la hidrólisis y por lo tanto de manera beneficiosa adecuado para todas las formulaciones donde se necesita un amplio rango de pH. Por ejemplo deo/anti-transpirantes y formulaciones de aclarado en el pelo.",
};
componente['158'] = {
    n: 'Manteca de Karite  = ',
    v: 0,
    t: "sol",
    c: "La manteca de karité es una grasa color marfil o amarillenta que se extrae de las nueces del árbol karité (Vitellaria paradoxa), que se encuentra en el África Occidental. La manteca o mantequilla de karité se usa en cosméticos, incluida la crema hidratante, así como en alimentos como el chocolate.",
};
componente['160'] = {
    n: 'Benzofenona - C<sub>13</sub>H<sub>10</sub>O  = ',
    v: 0,
    t: "liq",
    c: "Es una cetona aromática. Es un compuesto importante en fotoquímica orgánica, perfumería y como reactivo en síntesis orgánicas. Es una sustancia blanca cristalina, insoluble en agua y con olor a rosas. Su punto de fusión es de 49 °C y su punto de ebullición de 305-306 °C.",
};
componente['161'] = {
    n: 'Alcohol Cetoestearílico - CH<sub>3</sub>(CH<sub>2</sub>)<sub>n</sub>OH  = ',
    v: 0,
    t: "liq",
    c: "Alcohol cetoestearílico, cetearil alcohol o alcohol cetilestearílico​ es una mezcla de alcoholes grasos, que consiste predominantemente en cetílico y estearílico y se clasifica como un alcohol graso. Se utiliza como una emulsión de estabilizador, agente opacificante, y espuma de impulsar surfactante, así como un agente para aumentar la viscosidad acuosa y no acuosa. Imparte un sensación emoliente a la piel y se puede utilizar en emulsiones de agua en aceite, emulsiones de aceite-en-agua, y formulaciones anhidras. Se utiliza comúnmente en acondicionadores para el cabello y otros productos para el cabello.",
};
componente['162'] = {
    n: 'Oleato de Decilo - C<sub>28</sub>H<sub>54</sub>O<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "Líquido oleoso, amarillento en masa, con ligero olor. Insoluble en agua, miscible con aceites y grasas. Densidad: aprox. 0,950 g/ml. Es un agente con propiedades reengrasantes de la piel, utilizado en la preparación de emulsiones tanto de fase externa acuosa como oleosa.",
};
componente['163'] = {
    n: 'Phenonip  = ',
    v: 0,
    t: "liq",
    c: "Se presenta como una solución límpida, transparente y ligeramente viscosa. Es un conservante antimicrobiano compuesto por Nipagines y fenoxietanol, bastante eficaz en la conservación de soluciones y semisólidos susceptibles de contaminación microbiana. La incorporación en pomadas o emulsiones no reviste el menor problema, ya que el Phenonip es soluble en el excipiente graso contenido en este tipo de semisólidos.",
};
componente['164'] = {
    n: 'Aceite de Zanahoria  = ',
    v: 0,
    t: "liq",
    c: "El aceite de zanahoria se puede ingerir y utilizar en el ámbito culinario, no obstante, su uso principal está basado en el apartado más puramente cosmético y reparador de la piel, el cabello y los problemas derivados, para con ello, ser un gran aliado de tu belleza diaria.",
};
componente['165'] = {
    n: 'Cera Lambritol  = ',
    v: 0,
    t: "liq",
    c: "Es una cera autoemulsionable No Iónica.",
};
componente['166'] = {
    n: 'Cera Lanette  = ',
    v: 0,
    t: "liq",
    c: "Es un emulsionante aniónico de origen vegetal para preparar emulsiones del tipo aceite-en-agua.",
};
componente['167'] = {
    n: 'Acido Lactico - H<sub>3</sub>C-CH(OH)-COOH  = ',
    v: 0,
    t: "liq",
    c: "El ácido láctico, o su forma ionizada, el lactato (del lat. lac, lactis, leche), también conocido por su nomenclatura oficial ácido 2-hidroxi-propanoico o ácido α-hidroxi-propanoico, es un compuesto químico que desempeña importantes roles en varios procesos bioquímicos, como la fermentación láctica.",
};
componente['168'] = {
    n: 'Espermaceti  = ',
    v: 0,
    t: "liq",
    c: "Es una cera o aceite blanquecino, que se conoce también como blanco de ballena está presente en las cavidades del cráneo del cachalote (Physeter macrocephalus) y en las grasas vascularizadas de todas las ballenas. El aceite de ballena se extrae del espermaceti mediante cristalización a 6°C, después de tratamiento con presión y una solución química de álcali cáustico. Forma cristales blancos brillantes duros pero aceitosos al tocarlos, y una textura y olor muy apropiado para la industria cosmética, trabajos en cueros y en lubricantes.",
};
componente['169'] = {
    n: 'Ricinoleato de Zinc  = ',
    v: 0,
    t: "sol",
    c: "El ricinoleato de zinc es la sal de zinc del ácido ricinoleico, un ácido graso que se encuentra en el aceite de ricino obtenido de las semillas de la planta de Ricinus communis",
};
componente['170'] = {
    n: 'Caolin Coloidal  = ',
    v: 0,
    t: "sol",
    c: "La arcilla blanca, también conocida como caolín o kaolín, posee tantas propiedades que se ha convertido para muchas en una rutina de belleza indispensable. Tiene aplicaciones sobre la piel y a nivel terapéutico, ya que es desinfectante, calmante y limpia las impurezas. Además, los resultados se suelen notar desde el primer uso.",
};
componente['171'] = {
    n: 'Estereato de Zinc  = ',
    v: 0,
    t: "sol",
    c: "Es un Polvo blanco de la reacción de sales de zinc y un ácido graso. Es insoluble en disolventes polares como el alcohol y éter, pero soluble en los hidrocarburos aromáticos. Es el mejor agente desmoldante entre todos los estearatos (jabones) metálicos. No contiene electrolitos y tiene un efecto hidrofóbico.",
};
componente['172'] = {
    n: 'Carbonato de Magnesio - MgCO<sub>3</sub>  = ',
    v: 0,
    t: "sol",
    c: "El carbonato de magnesio es un compuesto químico de fórmula MgCO<sub>3</sub>. Este sólido blanco existe en la naturaleza como mineral. Existen también varias formas hidratadas y básicas del carbonato de magnesio como minerales. Adicionalmente, el MgCO3 tiene varias aplicaciones y usos.",
};
componente['173'] = {
    n: 'Acido Graso = ',
    v: 0,
    t: "sol",
    c: "Son componentes naturales de las grasas y los aceites. Tomando como referencia su estructura química ",
};
componente['174'] = {
    n: 'Metanol-  CH<sub>3</sub>OH (CH<sub>4</sub>O) = ',
    v: 0,
    t: "sol",
    c: "Líquido incoloro y muy tóxico, obtenido por destilación de la madera a baja temperatura o mediante la reacción del monóxido de carbono y el hidrógeno, que se emplea para desnaturalizar el alcohol etílico y como aditivo de combustibles líquidos. ",
};
componente['175'] = {
    n: 'Propanol - C<sub>3</sub>H<sub>8</sub>O = ',
    v: 0,
    t: "sol",
    c: "Es un alcohol incoloro, muy miscible con el agua. Es comúnmente llamado propanol, n-propanol o alcohol propílico. ",
};
componente['176'] = {
    n: 'Amina De Coco Primaria = ',
    v: 0,
    t: "sol",
    c: "Es un liquido viscoso de color amarillo ambar, con un olor caracteristico, no es considerado volatil y es soluble en agua. Tiene buenas propiedades de solvencia, baja propiedades espumeantes y son estables quimicamente.",
};
componente['177'] = {
    n: 'Amina De Coco Terciaria = ',
    v: 0,
    t: "sol",
    c: "Aminas terciarias puras no pueden formar puentes de hidrógeno, sin embargo pueden aceptar enlaces de hidrógeno con moléculas como el nitrogeno.",
};
componente['178'] = {
    n: 'Ethilen Glicol - C<sub>2</sub>H<sub>6</sub>O<sub>2</sub> = ',
    v: 0,
    t: "sol",
    c: "es un compuesto químico que pertenece al grupo de los dioles. Es un líquido transparente, incoloro, ligeramente espeso como el almíbar y leve sabor dulce.",
};
componente['179'] = {
    n: 'Etoxifenol 10 = ',
    v: 0,
    t: "sol",
    c: "Es un éter y son los compuestos que presentan un puente oxígeno entre dos átomos de carbono.",
};
componente['180'] = {
    n: 'Acido Aquil Lauril Neutro = ',
    v: 0,
    t: "sol",
    c: "Para Fabricacion de jabon en polvo uso industrial y hospitales comedores lavanderias",
};
componente['181'] = {
    n: 'Silicato De Sodio -  Na<sub>2</sub>SiO<sub>3</sub> = ',
    v: 0,
    t: "sol",
    c: "Es una sustancia inorgánica, que se encuentra en soluciones acuosas y también en forma sólida en muchos compuestos, entre ellos el cemento, impermeabilizadores, refractores, y procesos textiles.",
};
componente['182'] = {
    n: 'Carboxi Etil Celulosa = ',
    v: 0,
    t: "sol",
    c: "Sal soluble en agua para multitud de aplicaciones. Espesante, estabilizante, emulsificante y relleno son algunas de sus propiedades, utilizándose en infinitud de industrias",
};
componente['183'] = {
    n: 'Goma Arábica = ',
    v: 0,
    t: "sol",
    c: "Es un polisacárido de origen natural. Se extrae de la resina de ciertas variedades de la Acacia. Originalmente a partir del árbol o arbusto Acacia nilotica",
};
componente['184'] = {
    n: 'Yeso en Polvo  = ',
    v: 0,
    t: "sol",
    c: "Como material de construcción, es un producto elaborado a partir de un mineral natural denominado igualmente yeso o aljez sulfato de calcio dihidrato: CaSO<sub>4</sub>·2H<sub>2</sub>O, mediante deshidratación, que una vez amasado con agua, puede ser utilizado directamente. Se le puede añadir otras sustancias químicas para modificar sus características de fraguado, resistencia, adherencia, retención de agua y densidad.",
};
componente['185'] = {
    n: 'Carbonato de Sodio o Carbonato Sódico - Na₂CO₃  = ',
    v: 0,
    t: "sol",
    c: "El carbonato de sodio o carbonato sódico es una sal blanca y translúcida de fórmula química, usada entre otras cosas en la fabricación de jabón, vidrio y tintes.",
};
componente['186'] = {
    n: 'Grafito en Polvo - C  = ',
    v: 0,
    t: "sol",
    c: "El grafito es una de las formas alotrópicas en las que se puede presentar el carbono junto al diamante, los fullerenos, los nanotubos y el grafeno. A presión atmosférica y temperatura ambiente es más estable el grafito que el diamante. Sin embargo, la descomposición del diamante es tan extremadamente lenta que sólo es apreciable a escala geológica.",
};
componente['187'] = {
    n: 'Aceite Mineral Parafinado  = ',
    v: 0,
    t: "liq",
    c: "Un aceite mineral parafinado es un subproducto líquido de la destilación del petróleo con agregados de parafina que lo hacen ser un producto tipico de materia prima de lubricantes, afloja tornillos, etc.",
};
componente['188'] = {
    n: 'Formaldehido o Metanal - H₂C=O  = ',
    v: 0,
    t: "liq",
    c: "Es un compuesto químico, más específicamente un aldehído altamente volátil y muy inflamable, de fórmula H₂C=O. Se obtiene por oxidación catalítica del alcohol metílico.",
};
componente['189'] = {
    n: 'Butilcellusolve - C<sub>6</sub>H<sub>14</sub>O<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "Es un líquido volátil, incoloro, de olor dulce agradable. Poco soluble en agua pero miscible en la mayoría de los disolventes orgánicos. Es prácticamente ininflamable y no explosivo en condiciones normales de utilización.",
};
componente['190'] = {
    n: 'Hidroxido de Sodio o Soda Cautica al 50% - NaOH  = ',
    v: 0,
    t: "liq",
    c: "El hidróxido de sodio (NaOH), hidróxido sódico o hidrato de sodio, también conocido como soda cáustica o sosa cáustica, es un hidróxido cáustico usado en la industria (principalmente como una base química) en la fabricación de papel, tejidos, y detergentes.",
};
componente['191'] = {
    n: 'Sulfonato de Alquilbenceno Lineal - Na+ -O<sub>3</sub>S-C<sub>6</sub>H<sub>4</sub>-CHR<sub>1</sub>R<sub>2</sub>  = ',
    v: 0,
    t: "sol",
    c: "El sulfonato de alquilbenceno lineal o dodecilbencenosulfonato de sodio es un componente de los detergentes de lavandería y productos de limpieza, muy empleado por sus propiedades como tensoactivo y por ser completamente biodegradable tanto aerobia como anaerobiamente. Es el tensioactivo aniónico más difundido a nivel mundial, suponiendo un 40% de todos los tensioactivos utilizados.",
};
componente['192'] = {
    n: 'Tripolifosfato de Sodio - Na<sub>5</sub>P<sub>3</sub>O<sub>10</sub> = ',
    v: 0,
    t: "sol",
    c: "Se trata de la sal de sodio del polifosfato penta-anión, que es la base conjugada del ácido trifosfórico. Se produce a gran escala como un componente de muchos productos domésticos e industriales, especialmente detergentes.",
};
componente['193'] = {
    n: 'Ether di Cloro Metilo - CH<sub>3</sub>OCH<sub>2</sub>Cl<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "Se utiliza como un agente alquilante y disolvente industrial para la fabricación de cloruro de dodecil, repelentes de agua, resinas de intercambio iónico, polímeros.",
};
componente['194'] = {
    n: 'Monoetanolamina - HO-CH<sub>2</sub>-CH<sub>2</sub>-NH<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "También llamada 2-aminoetanol o monoetanolamina, abreviado como ETA o MEA, es un compuesto químico orgánico que es tanto una amina primaria, (debido a un grupo amino en su molécula) como un alcohol primario (debido a un grupo hidroxilo). Como en el caso de otras aminas, la monoetanolamina actúa como una base débil. La etanolamina es un líquido tóxico, inflamable, corrosivo, incoloro y viscoso, con un olor similar al amoníaco.",
};
componente['195'] = {
    n: 'Dietanolamina - C<sub>4</sub>H<sub>11</sub>NO<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "La Dietanolamina es un compuesto químico orgánico que se utiliza en disolventes, emulsionantes y aplicaciones detergentes. Abreviado a menudo como la DEA, es tanto una amina secundaria como un dialcohol. Un dialcohol tiene dos grupos hidroxilo funcionales en su molécula. Al igual que otras aminas, la dietanolamina actúa como una base débil.",
};
componente['196'] = {
    n: 'Lavanda Francesa  = ',
    v: 0,
    t: "liq",
    c: "Esta esencia se utiliza principalmente en industrias de productos de tocador y de perfumería.",
};
componente['197'] = {
    n: 'Lavanda Spica = ',
    v: 0,
    t: "liq",
    c: "Esta esencia se utiliza principalmente en industrias de productos de tocador y de perfumería.",
};
componente['198'] = {
    n: 'Bergamota = ',
    v: 0,
    t: "liq",
    c: " En función de sus usos de este aceite pueden incluir aceites de cítricos como la naranja amarga, mandarina o el limón.",
};
componente['199'] = {
    n: 'Extracto de Clavo = ',
    v: 0,
    t: "liq",
    c: "Es un líquido oleoso de color amarillo pálido extraído de ciertos aceites esenciales",
};
componente['200'] = {
    n: 'Ftalato Dietil = ',
    v: 0,
    t: "liq",
    c: "Es un líquido incoloro que tiene un sabor amargo desagradable. Este compuesto sintético es usado comúnmente para dar flexibilidad a plásticos.",
};
componente['201'] = {
    n: 'Aceite Esencial de Limón = ',
    v: 0,
    t: "liq",
    c: "Tiene múltiples propiedades altamente beneficiosas para la salud general y mejorar el bienestar. Sus propiedades más destacadas son la del aumento de glóbulos blancos y la diurética.",
};
componente['202'] = {
    n: 'Aceite Esencial de Bergamota = ',
    v: 0,
    t: "liq",
    c: "Es conocido que consumir cítricos es muy beneficioso para el organismo, pero quizás pocos saben que dentro de este tipo de frutos existe uno que no se usa en la alimentación y, sin embargo, posee diversas propiedades medicinales: la bergamota.",
};
componente['203'] = {
    n: 'Aceite Esencial de Neroli = ',
    v: 0,
    t: "liq",
    c: "Es usado para para aromatizar el hogar, para aromatizar los productos caseros de limpieza o para elaborar perfumes naturales.",
};
componente['204'] = {
    n: 'Aceite esencial de naranja = ',
    v: 0,
    t: "liq",
    c: " el aceite esencial de naranja proporciona un alivio rápido y eficaz ante la inflamación, ya sea interna o externa.",
};
componente['205'] = {
    n: 'Aceite Esencial de Petitgrain = ',
    v: 0,
    t: "liq",
    c: "Es ampliamente usado en la industria de la perfumería, dando a los aerosoles corporales, fragancias, lociones y colonias un toque fresco y herbáceo muy popular tanto entre hombres como entre mujeres.",
};
componente['206'] = {
    n: 'Aceite Esencial de Romero = ',
    v: 0,
    t: "liq",
    c: "es utilizado en velas, perfumes, aceites para baño, aromatizantes y cosméticos, para aumentar la energía mental al momento de ser inhalado.es utilizado en velas, perfumes, aceites para baño, aromatizantes y cosméticos, para aumentar la energía mental al momento de ser inhalado.",};
componente['207'] = {
    n: 'Aceite Esencial de Rosa = ',
    v: 0,
    t: "liq",
    c: "Se usar para aromatizar cualquier producto casero de limpieza, para aromatizar el hogar y para elaborar perfumes naturales, colonias y desodorantes",};
componente['208'] = {
    n: 'Aceite Esencial de Lavanda = ',
    v: 0,
    t: "liq",
    c: "Se usar para aromatizar cualquier producto casero de limpieza, para aromatizar el hogar y para elaborar perfumes naturales, colonias y desodorantes",
};componente['209'] = {
    n: 'Kaolín - Al<sub>2</sub>Si<sub>2</sub>O<sub>5</sub>(OH)<sub>4</sub>  = ',
    v: 0,
    t: "sol",
    c: "El Kaolín es una arcilla de color blanco, beige o rosa, según su origen. Su nombre procede del primer yacimiento descubierto en China, la colina Kaoling. La caolinita es un mineral de arcilla que forma parte del grupo de minerales industriales, con la composición química Al<sub>2</sub>Si<sub>2</sub>O<sub>5</sub>(OH)<sub>4</sub>. Se trata de un mineral tipo silicato estratificado, con una lámina de tetraedros unida a través de átomos de oxígeno en una lámina de octaedros de alúmina.1​ Las rocas que son ricas en caolinita son conocidas como caolín o arcilla de China.",
};componente['210'] = {
    n: 'Jabón de coco potásico  = ',
    v: 0,
    t: "liq",
    c: "Solución concentrada de jabón potásico de ácidos grasos provenientes del aceite de coco. Se usa para elaborar jabón líquido, para preparar y emulsificar y como detergente acaricida de contacto.",
};componente['211'] = {
    n: 'Verde Malaquita - C<sub>23</sub>H<sub>25</sub>ClN<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "El verde de malaquita es un colorante verde. Aunque se llama verde malaquita, este colorante no se prepara a partir de la malaquita mineral, pues el nombre sólo viene de la similitud de color. Tiene uso como colorante y es activo frente a una gran variedad de parásitos externos y agentes patógenos como hongos, bacterias, etc. Su principal aplicación es para el tratamiento contra parásitos protozoos de agua dulce.",
};componente['212'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['213'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['214'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['215'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['216'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['217'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['218'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['219'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['220'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['221'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['222'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['223'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['224'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['225'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['226'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['227'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['228'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['229'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['230'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['231'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['232'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['233'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['234'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['235'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['236'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['237'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['238'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['239'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['240'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['241'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['242'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['243'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['244'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['245'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['246'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['247'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};componente['248'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};
componente['500'] = {
    n: 'Asfalto Liquido  = ',
    v: 0,
    t: "sol",
    c: "El asfalto, también denominado chapopote, es un material viscoso, pegajoso y de color plomo (gris oscuro). Se utiliza mezclado con arena o gravilla para pavimentar caminos y como revestimiento impermeabilizante de muros y tejados. En las mezclas asfálticas se usa como aglomerante para la construcción de carreteras, autovías y autopistas. Está presente en el petróleo crudo y compuesto casi por completo de betún bitumen.",
};
componente['501'] = {
    n: 'Piedra Sodica - Na<sub>8</sub>Al<sub>6</sub>Si<sub>6</sub>O<sub>24</sub>Cl<sub>2</sub>  = ',
    v: 0,
    t: "sol",
    c: "Sodalita significa piedra de sodio y fue llamada así al comprobar durante los primeros análisis químicos del mineral su alto contenido en Sodio. Es también llamada, con frecuencia, piedra sodálite.",
};
componente['502'] = {
    n: 'Piedra Calcica  = ',
    v: 0,
    t: "sol",
    c: "Es el componente principal de minerales como la calcita o el aragonito y de rocas como la caliza y sus variedades (travertino, creta, carniola) o el mármol, procedente del metamorfismo de calizas.",
};
componente['503'] = {
    n: 'Piedra Pomez  = ',
    v: 0,
    t: "sol",
    c: "Es una roca ígnea volcánica vítrea, con baja densidad —flota en el agua— y muy porosa, de color blanco o gris. Cuando se refiere a la piedra pómez en lo que respecta a sus posibles aplicaciones industriales, también puede ser conocida como puzolana. ",
};
componente['504'] = {
    n: 'Solvente Xilol - ‎C<sub>6</sub>H<sub>4</sub>(CH<sub>3</sub>)<sub>2</sub>  = ',
    v: 0,
    t: "sol",
    c: "Componentes y suministros de fabricación / Pinturas, imprimantes y acabados / Disolventes y diluyentes para pinturas. ",
};
componente['505'] = {
    n: 'Kerosene Desodorizado  = ',
    v: 0,
    t: "sol",
    c: "Es un líquido inflamable y transparente (o con ligera coloración amarillenta), mezcla de hidrocarburos, que se obtiene de la destilación del petróleo natural.  ",
};
componente['506'] = {
    n: 'Butanol - C<sub>4</sub>H<sub>10</sub>O  = ',
    v: 0,
    t: "sol",
    c: "Un alcohol que se presenta como un líquido incoloro de fuerte olor a vino. El butanol o n-butanol,es un alcohol butílico que se emplea como disolvente: el butanol disolvió algunos componentes necesarios en la investigación.",
};
componente['507'] = {
    n: 'Agua Destilada  = ',
    v: 0,
    t: "liq",
    c: "Es aquella sustancia cuya composición se basa en la unidad de moléculas de H2O y ha sido purificada o limpiada mediante destilación.",
};
componente['508'] = {
    n: 'Diethilen Glicol - C<sub>4</sub>H<sub>10</sub>O<sub>3</sub>	 = ',
    v: 0,
    t: "liq",
    c: "Es un líquido viscoso, incoloro e inodoro de sabor dulce. Es higroscópico, miscible en agua, alcohol, etilenglicol, etc. ",
};
componente['509'] = {
    n: 'Aceite de Silicona  = ',
    v: 0,
    t: "liq",
    c: "La silicona es uno de los productos más utilizados en la industria gracias a sus excelentes propiedades adhesivas. Se trata de un polímero que contiene oxígeno e hidrógeno en su composición, como la mayoría de materiales orgánicos, además del elemento que le da su nombre: el silicio.",
};
componente['510'] = {
    n: 'Emulsion de Silicon al 36%  = ',
    v: 0,
    t: "liq",
    c: "Se define normalmente una emulsión como un sistema de dos líquidos inmiscibles, uno de los cuales se dispersa en el otro en forma de pequeñas gotitas. Es preciso ampliar algo esta definición, para incluir todos los sistemas que, en la práctica industrial, se definen normalmente como emulsiones.",
};
componente['511'] = {
    n: 'Solucion Saturada de Cloruro de Sodio  = ',
    v: 0,
    t: "liq",
    c: "Agua con alta concentracion de Sal Comun, de 6 a 8 cucharadas grandes por cada 500 cc.",
};
componente['512'] = {
    n: 'Acetona - CH<sub>3</sub>(CO)CH<sub>3</sub>  = ',
    v: 0,
    t: "liq",
    c: "La acetona o propanona es un compuesto químico de fórmula CH<sub>3</sub>(CO)CH<sub>3</sub> del grupo de las cetonas que se encuentra naturalmente en el medio ambiente. A temperatura ambiente se presenta como un líquido incoloro de olor característico. Se evapora fácilmente, es inflamable y es soluble en agua. La acetona sintetizada se usa en la fabricación de plásticos, fibras, medicamentos y otros productos químicos, así como disolvente de otras sustancias químicas.",
};
componente['513'] = {
    n: 'Nafta o Gasolina  = ',
    v: 0,
    t: "liq",
    c: "El término nafta puede hacer referencia a: gasolina, combustible para automóviles; éter de petróleo, compuesto químico que se emplea como disolvente.",
};
componente['514'] = {
    n: 'Toluol, Tolueno o Metilbenceno - C<sub>6</sub>H<sub>5</sub>CH<sub>3</sub>  = ',
    v: 0,
    t: "liq",
    c: "Es utilizado en combustibles para automóviles y aviones; como disolvente de pinturas, barnices, hules, gomas, etil celulosa, poliestireno, polialcohol vinílico, ceras, aceites y resinas, reemplazando al benceno.",
};
componente['515'] = {
    n: 'Alginato Sodico  = ',
    v: 0,
    t: "sol",
    c: "El alginato es un polisacárido aniónico distribuido ampliamente en las paredes celulares de las algas marinas pardas. Estas sustancias corresponden a polímeros orgánicos derivados del ácido algínico.",
};
componente['516'] = {
    n: 'Tergitol Tambien llamado Nonilfenol Etoxilado  = ',
    v: 0,
    t: "liq",
    c: "Tipo de Surfactante: No iónico, Emulsionante y estabilizador altamente soluble en agua, Excelente poder detergente, Eficaz a altas temperaturas, Características de solubilidad versátiles, Agentes humectantes y estabilizadores.",
};
componente['517'] = {
    n: 'Acetato de Amilo o Isoamilo - CH<sub>3</sub>COOCH<sub>2</sub>CH<sub>2</sub>CH(CH<sub>3</sub>)<sub>2</sub>  = ',
    v: 0,
    t: "liq",
    c: "El acetato de isoamilo, denominado también aceite de plátano o aceite de banana, es un compuesto orgánico de fórmula CH<sub>3</sub>COOCH<sub>2</sub>CH<sub>2</sub>CH(CH<sub>3</sub>)<sub>2</sub> que es un éster del alcohol isoamílico y el ácido acético. Es un líquido incoloro con aroma a bananas (y ligeramente a pera) y por eso algunas industrias alimentarias lo emplean como aromatizante. La denominación aceite de banana o aceite de plátano se aplica tanto al acetato de isoamilo empleado como aromatizante, o a las mezclas de acetato de isoamilo, acetato de amilo y otros compuestos.",
};
componente['518'] = {
    n: 'S.T.P.P. o Tripolifosfato de sodio - Na<sub>5</sub>P<sub>3</sub>O<sub>10</sub>  = ',
    v: 0,
    t: "sol",
    c: "El trifosfato de sodio (STP), a veces STPP tripolifosfato de sodio o TPP,​ es un compuesto inorgánico de fórmula Na<sub>5</sub>P<sub>3</sub>O<sub>10</sub>. Se trata de la sal de sodio del polifosfato penta-anión, que es la base conjugada del ácido trifosfórico. Se produce a gran escala como un componente de muchos productos domésticos e industriales, especialmente detergentes.",
};
componente['519'] = {
    n: 'Purpurato de Sodio  = ',
    v: 0,
    t: "sol",
    c: "Producto con cualidades quimicas que evita la oxidación, corrosión, incrustaciones en el motor y radiador, aparte de sus excelentes cualidades como refrigerante.",
};
componente['520'] = {
    n: 'Amina Primaria  = ',
    v: 0,
    t: "liq",
    c: "Aminas primarias: etilamina, anilina, etc. Las aminas son compuestos químicos orgánicos que se consideran como derivados del amoníaco y resultan de la sustitución de uno o varios de los hidrógenos de la molécula de amoníaco por otros sustituyentes o radicales. Según se sustituyan uno, dos o tres hidrógenos, las aminas son primarias, secundarias o terciarias, respectivamente.",
};
componente['521'] = {
    n: 'Amina Terciaria  = ',
    v: 0,
    t: "liq",
    c: "Aminas terciarias: trimetilamina, dimetilbencilamina, etc. Las aminas son compuestos químicos orgánicos que se consideran como derivados del amoníaco y resultan de la sustitución de uno o varios de los hidrógenos de la molécula de amoníaco por otros sustituyentes o radicales. Según se sustituyan uno, dos o tres hidrógenos, las aminas son primarias, secundarias o terciarias, respectivamente.",
};
componente['522'] = {
    n: 'Etoxifenol  = ',
    v: 0,
    t: "liq",
    c: "Es un éter y son los compuestos que presentan un puente oxígeno entre dos átomos de carbono(C­O­C). Los grupos unidos al oxígeno pueden ser alquilo o arilo. Si los grupos unidos aloxígeno son iguales, el éter es simple y si son diferentes, el éter es mixto.Comúnmente, se nombran los dos grupos por orden alfabético, seguido de la palabra éter,o bien, como éter alquílico. Actualmente, se los nombra como alcanos con sustituyentes alcoxi (alcoxialcano).",
};
componente['523'] = {
    n: 'Sulfato de Magnesio - MgSO<sub>4</sub>·<sub>7</sub>H<sub>2</sub>O  = ',
    v: 0,
    t: "sol",
    c: "El sulfato de magnesio o sulfato magnésico, de nombre común sal de Epsom (o sal inglesa), es un compuesto químico que contiene magnesio, y cuya fórmula es MgSO<sub>4</sub>·<sub>7</sub>H<sub>2</sub>O. El sulfato de magnesio sin hidratar MgSO<sub>4</sub> es muy poco frecuente y se emplea en la industria como agente secante.",
};
componente['524'] = {
    n: 'Bisulfato de Sodio - NaHSO<sub>4</sub>  = ',
    v: 0,
    t: "sol",
    c: "Es una sal ácida formada por la neutralización parcial del ácido sulfúrico con una base de sodio (normalmente hidróxido de sodio o cloruro de sodio).",
};
componente['525'] = {
    n: 'Vermiculita - Mg0,<sub>7</sub> (Mg,Fe,Al)<sub>6</sub> (Si,Al)<sub>8</sub>O<sub>20</sub> (OH)<sub>4</sub> ·8H<sub>2</sub>O  = ',
    v: 0,
    t: "sol",
    c: "La vermiculita es un mineral formado por silicatos de hierro o magnesio, del grupo de las micas. La vermiculita se describió por primera vez en 1824 por una ocurrencia en Millbury, Massachusetts. Su nombre deriva del latín vermiculare, 'por criar gusanos', por la manera en que exfolia cuando se calienta.",
};
componente['526'] = {
    n: 'Aceite de Motor  = ',
    v: 0,
    t: "liq",
    c: "Se llama aceite de motor, por extensión, a todo aceite que se utiliza para lubricar los motores de combustión interna. Su propósito principal es lubricar las partes móviles reduciendo la fricción.",
};
componente['527'] = {
    n: 'Aguarras  = ',
    v: 0,
    t: "liq",
    c: "El aguarrás o trementina es un líquido volátil e incoloro producido mediante la destilación de la resina, o miera, de diversas especies de coníferas y de varias especies de árboles terebintáceos. Es usada como disolvente de pinturas, materia prima para la fabricación de compuestos aromáticos sintéticos y algunos desinfectantes. Es un líquido casi incoloro de olor característico. En la actualidad se obtiene en grandes cantidades como subproducto de la producción de celulosa (materia prima de la fabricación de papel) en industrias que usan como materia prima coníferas.",
};
componente['528'] = {
    n: 'Mergal  = ',
    v: 0,
    t: "liq",
    c: "Es un preservativo compuesto por una adición de aminas e izotiazolinonas para sistemas de dispersión acuosa, pinturas, adhesivos, pastas pigmentarias. Es un líquido amarillo.",
};
componente['529'] = {
    n: 'Emulsion de Silicon al 60%  = ',
    v: 0,
    t: "liq",
    c: "Se define normalmente una emulsión como un sistema de dos líquidos inmiscibles, uno de los cuales se dispersa en el otro en forma de pequeñas gotitas. Es preciso ampliar algo esta definición, para incluir todos los sistemas que, en la práctica industrial, se definen normalmente como emulsiones.",
};
componente['530'] = {
    n: 'Varsol  = ',
    v: 0,
    t: "liq",
    c: "Hidrocarburo volátil proveniente de la destilación de naftas o de gasolina natural. El Varsol es utilizado para la fabricación de resinas, ceras y betunes, para tintorerías, lavanderías y limpieza en general. En el hogar y oficinas es comúnmente utilizado como des-manchador.",
};
componente['531'] = {
    n: 'Neodol  = ',
    v: 0,
    t: "liq",
    c: "son alcoholes de alta pureza, alta linealidad alcoholes primarios que normalmente contienen 75-85% de su peso normal alcoholes. NEODOL alcohol reacciones químicas típicas de los alcoholes primarios. La facilidad de etoxilación y la sulfonación por medios convencionales derivados hace que la adecuada para un amplio espectro de aplicaciones de surfactante.",
};
componente['532'] = {
    n: 'Lanolina Anhidra  = ',
    v: 0,
    t: "sol",
    c: "Sustancia untuosa amarilla pardusca, con olor leve. Funde entre 36 - 42º C Es insoluble en agua, pero se mezcla sin separación con aproximadamente con el doble de su peso de agua. Es escasamente soluble en alcohol frío, más soluble en alcohol caliente; y muy soluble en éter, cloroformo, disulfuro de carbono, acetona y éter de petróleo.",
};
componente['533'] = {
    n: 'Acetato de Polivinilo o PVA - (C<sub>4</sub>H<sub>6</sub>O<sub>2</sub>)<sub>n</sub> = ',
    v: 0,
    t: "liq",
    c: "Conocido comúnmente como adhesivo vinilico, cola o cola fría (en Chile), es un polímero obtenido mediante la polimerización del acetato de vinilo, descubierto por el químico alemán Fritz Klatte en 1912. Para preparar alcohol de polivinilo se usa la hidrólisis del polímero (ya sea parcial o total). Se presenta comercialmente en forma de emulsión, como adhesivo para materiales porosos, en especial la madera. A una de sus variedades se la conoce como Resistol o Resistol 850, la marca de la industria que lo produce.",
};
componente['534'] = {
    n: 'Dibutilftalato - C<sub>16</sub>H<sub>22</sub>O<sub>4</sub>  = ',
    v: 0,
    t: "sol",
    c: "El ftalato de dibutilo también conocido como DBP (Dibutilftalato), es un compuesto orgánico usado en la industria como plastificante. También se utiliza como un aditivo en adhesivos, tintas para impresoras y en productos cosméticos. Es soluble en varios solventes orgánicos como por ejemplo, alcohol, éter y benceno.",
};
componente['535'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};
componente['536'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};
componente['537'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};
componente['538'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};
componente['539'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};
componente['540'] = {
    n: '  = ',
    v: 0,
    t: "liq",
    c: " ",
};

function qi(a){
for (ab in componente)
{
    ncompo = a;
    titulocompo = ncompo.replace(" = ", "");
    //componente['ab'].n = "<b>" + componente['ab'].n+"</b>";
    b = titulocompo;
}
return b;
}

//a = Componente
//b = Porcentaje
function p(b){
    var UI = document.getElementById('entrada').value;
valor = UI * b / 100;
return valor;
}

//FUNCTION QUE QUITARA SIGNO DE IGUAL A CADA COMPONENTE
function quitarigual(){
    for (ab in componente)
    {
        ncompo = componente['ab'].n;
        titulocompo = ncompo.replace(" = ", "");
        //componente['ab'].n = "<b>" + componente['ab'].n+"</b>";
        componente['ab'].n = titulocompo;
    }
    return componente['ab'].n;
  }

//FUNCTION QUE COLOCARA SIGNO DE IGUAL A CADA COMPONENTE
  function ponerigual(){
    for (ab in componente)
    {
        ncompo = componente['ab'].n;
        igual = "<b> = </b>";
        componente['ab'].n = ncompo.concat(igual);
    }
    return componente['ab'].n;
  }

//FUNCTION QUE IMPORTARA EL SCRIPT DE KERNEL EXTRA SI EXISTE
function importa() {
    var archivo = document.querySelector("script[src='extra/pag/libreria/js/kernelextra.js']");
    if (archivo) {
      document.write("<script type='text/javascript' src='extra/pag/libreria/js/kernelextra.js'></script>");
    }
  }
  importa();



function tratamiento_n(){
    for (ab in componente)
    {
        ncompo = componente['ab'].n;
        titulo = ncompo.replace("=", "");
        titulocompo = ncompo;
        //componente['ab'].n = "<b>" + componente['ab'].n+"</b>";
        componente['ab'].n = "<b data-toggle='popover' title='<b>" + titulo + "</b>' data-content='" + componente['ab'].c + "'>" + titulocompo + "</b>";
    }
    return componente['ab'].n;
}

//function tratamiento_n(){
//    for (ab in componente)
//    {
//        ncompo = componente['ab'].n;
//        titulo = ncompo.replace("=", "");
//        titulocompo = ncompo;
//
//        if (componente.hasOwnProperty('ab')) {
//            componente['ab'].n = "<b>" + titulo + "</b>";
//        }
//    }
//    return componente['ab'].n;
//}
tratamiento_n();

function updateIndicator() {
    document.getElementById('indicator').innerHTML = navigator.onLine ? "<div class='alert alert-info alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Se ha Detectado un Funcionamiento Perfecto!</strong> Usted Se encuentra conectado/a a Internet.</div>" : "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Usted se ha Desconectado de Internet!</strong>  hay elementos de este curso que se desactivaran, al recuperar la conexion a internet estaran disponibles de nuevo.</div>";
}

function updateFrame() {
    document.getElementById('frame').innerHTML = navigator.onLine ? '<iframe scrolling="no" src="../publicidad/indexon.html" name="publicidad" frameborder="0" width="100%" height="90"></iframe>' : '<iframe align="center" scrolling="no" src="../publicidad/indexoff.html" name="publicidad" frameborder="0" width="100%" height="90"></iframe>';
}

function updateLi() {
    document.getElementById('rotator').innerHTML = navigator.onLine ? '<iframe marginheight="0" marginwidth="0" align="center" margin="0 auto" scrolling="no" src="./publicidad/indexon.html" name="publicidad" frameborder="0" width="700" height="95" style="border: 0px; vertical-align: bottom;"></iframe>' : '<iframe marginheight="0" marginwidth="0" align="center" scrolling="no" src="./publicidad/indexoff.html" name="publicidad" frameborder="0" width="700" height="95" style="border: 0px; vertical-align: bottom;"></iframe>';
}

function updateP() {
    document.getElementById('rotator').innerHTML = navigator.onLine ? '<iframe marginheight="0" marginwidth="0" align="center" margin="0 auto" scrolling="no" src="https://goo.gl/rpjfB6" name="publicidad" frameborder="0" width="700" height="95" style="border: 0px; vertical-align: bottom;"></iframe>' : '<iframe marginheight="0" marginwidth="0" align="center" scrolling="no" src="./pag/publicidad/indexoff.html" name="publicidad" frameborder="0" width="700" height="95" style="border: 0px; vertical-align: bottom;"></iframe>';
    document.getElementById('mensaje').innerHTML = navigator.onLine ? "<iframe scrolling='no' src="+ mensajeactualizacion +" name='texto' width='100%' height='60' frameborder='0'></iframe>" : "<div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> COMO NO ESTA CONECTADO A INTERNET ESTE SISTEMA NO ESTA EN CAPACIDAD DE VERIFICAR SI POSEE LA ULTIMA VERSION DE ESTE CURSO, FAVOR CONECTESE A INTERNET Y DE MANERA AUTOMATICA SE EFECTUARA ESTA VERIFICACION.</div>";


}

function updateextra(){
    document.getElementById('mensajeextra').innerHTML = navigator.onLine ? "<iframe scrolling='no' src="+ mensajeextra +" name='texto' width='100%' height='60' frameborder='0'></iframe>" : "<div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign'aria-hidden='true'></span><span class='sr-only'>Error:</span> COMO NO ESTA CONECTADO A INTERNET ESTE SISTEMA NO ESTA EN CAPACIDAD DE VERIFICAR SI EXISTEN EXTENSIONES DISPONIBLES, FAVOR CONECTESE A INTERNET Y DE MANERA AUTOMATICA SE EFECTUARA ESTA VERIFICACION.</div>";
}

// PARA PRUEBAS
function onOff() {
    document.getElementById('rotator').innerHTML = navigator.onLine ? 'EN LINEA' : 'FUERA DE LINEA';
}
/* Case 1 */
window.addEventListener("online", function() {
    document.getElementById('test1').innerHTML = "online";
}, false);

window.addEventListener("offline", function() {
    document.getElementById('test1').innerHTML = "OFFLINE";
}, false);

ncompoa = "";
ncompo = "";


function botonera() {

    var avl = a + v + l;

    botonera = "<div class = 'container-fluid'> <div class = 'row'> " +
        "<div class = 'col-xs-12 col-sm-3 col-md-3 col-lg-3'> <div class = 'dropdown'> <button title='Ingrese a la seccion que desee explorar' data-hover='dropdown' class = 'btn btn-sm btn-primary dropdown-toggle col-xs-12' type = 'button' id = 'dropdownMenu1' data-toggle = 'dropdown' aria-haspopup = 'true' aria-expanded = 'true'>Secciones  <span class = 'caret'> </span> </button> <ul class = 'dropdown-menu' aria-labelledby = 'dropdownMenu1'> " + "<li><a onclick='bienvenida();' name='Bienvenida' title='Cargar Inicio del Curso' href='#Bienvenida'>Cargar Bienvenida " + h + " </a></li> "+

        "<li><a onclick='formulas(), primerselect();' name='Formulas' title='Formulas' href='#Formulas'>Calculo de Formulas " + c + i + " </a></li> " + "<li><a onclick='nfpa();' name='NFPA' title='Rombo de Seguridad NFPA' href='#NFPA'>Rombo NFPA " + i + " </a></li> " + "<li><a onclick='industrial();' name='industrial' title='Instalaciones Industriales' href='#industrial'>Instalaciones Industriales " + i + " </a></li> " + "<li><a onclick='ph();' name='ph' title='Leccion sobre PH' href='#ph'>Niveles de PH y Neutralizacion " + i + " </a></li> " + "<li><a onclick = 'glosario();' title = 'Glosario de Terminos' name = 'Glosario de Terminos' href='#glosario'>Glosario de Terminos <span class = 'glyphicon glyphicon-book' aria-hidden = 'true'> </span> " + i + " </a></li> " +
        "</ul></div> </div> " +
        "<div class = 'col-xs-12 col-sm-3 col-md-3 col-lg-3'> <a href = '#extra' title = 'Ver Extensiones Instaladas' name = 'Extensiones' onclick = 'extra();' class = 'btn btn-sm btn-primary btn-block'> Extensiones  " + e + " </a> </div> " +
        "<div class = 'col-xs-12 col-sm-3 col-md-3 col-lg-3'> <a onclick='formulas(), primerselect();' name='Formulas' title='Formulas' href='#Formulas' class = 'btn btn-sm btn-primary btn-block'>Calculo de Formulas " + c + i + " </a> </div> " +
        "<div class = 'col-xs-12 col-sm-3 col-md-3 col-lg-3'> <a href = '#CREDITO' onclick = 'creditos();' title = 'Creditos' name = 'Creditos' class = 'btn btn-sm btn-primary btn-block'>Creditos <span class = 'glyphicon glyphicon-link' aria-hidden = 'true'> </span> </a> </div> </div> </div>";

    bienvenida();
    footer_site();

     document.getElementById('botonera').innerHTML = botonera,
    // document.getElementById('foot').innerHTML = foot,
     document.getElementById('contenido').innerHTML = contenido;
   // mensextra();


}


function footer_site(){

    foot = "<div class='navbar navbar-default '><div class='container-fluid'> <div class='row'> <div class='col-xs-12 col-sm-6'>Esta obra está bajo una <a class = 'clase6'  rel = 'license' target = '_blank' href = 'https://creativecommons.org/licenses/by-sa/3.0/ve/'> Licencia de Creative Commons </a> <br><a target = '_blank' rel = 'license' href = 'https://creativecommons.org/licenses/by-sa/3.0/ve/'> <img alt = 'Licencia de Creative Commons' style = 'border-width:0' src = 'media/imagen/88x31.png' /> </a><br> <strong>DEPOSITO LEGAL VENEZUELA: </strong> " + deposito + "</div> " +
    "<div class='col-xs-12 col-sm-6'> <strong>Visitanos en</strong> <a class = 'clase6' href = 'http://www.jesuministrosymas.com.ve' target = '_blank' name = 'J.E SUMINISTROS Y MAS, C.A.' id = 'link web' lang = 'es' title = 'J.E SUMINISTROS Y MAS, C.A.'> <strong> <em>www.jesuministrosymas.com.ve</em> </strong> </a> <br> <a href = 'https://twitter.com/JESYMCA' class = 'twitter-follow-button' data-show-count = 'false' data-lang = 'es' target='_blank'>Seguir a @JESYMCA</a><br> <a href = 'https://t.me/jesuministrosymas_canal' target='_blank'>Seguirnos en Telegram a @JESUMINISTROSYMAS</a> <br> <a href = 'http://bit.ly/WhatsApp_JE' target='_blank'>Contactanos por WhatsApp @JESUMINISTROSYMAS</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script> </div></div></div></div>    <script>$(document).ready(function () {$('[data-toggle=\"popover\"]').popover(); }); </script>";

    // document.getElementById('foot').innerHTML = foot;
}

function bienvenida(){
    contenido = "<div class = 'panel panel-info'> <div class = 'panel-heading'>  <h1 class = 'panel-title'><strong>Bienvenidos a este Aplicativo</strong></h1> </div> <div class = 'panel-body'> <div class = 'container-fluid'> <div> <!-- Nav tabs --> <ul class = 'nav nav-tabs' role = 'tablist'> <li role = 'presentation' class = 'active'> <a href = '#home' aria-controls = 'home' role = 'tab' data-toggle = 'tab'>Bienvenidos</a> </li> <li role = 'presentation'> <a href = '#profile' aria-controls = 'profile' role = 'tab' data-toggle = 'tab'>Uso de este Manual</a> </li> <li role = 'presentation'> <a href = '#contacto' aria-controls = 'contacto' role = 'tab' data-toggle = 'tab'>Contactenos</a> </li> <li role = 'presentation'> <a href = '#otros' aria-controls = 'otros' role = 'tab' data-toggle = 'tab'>Otros Cursos</a> </li> </ul> <!-- Tab panes --> <div class = 'tab-content'> <div role = 'tabpanel' class = 'tab-pane active' id = 'home'> <b> <h1>Bienvenido</h1> </b> <p>Le saludo y felicito por la adquisicion de este aplicativo, <b>APRENDA A FABRICAR PRODUCTOS DE LIMPIEZA.</b> </p> <p>Con esta entrega estas disfrutando de una de las actualizaciones mas versatiles que hemos elaborado, hemos reescrito y ampliado completamente el codigo y mejorado muchos aspectos que le permitiran acceder desde casi cualquier dispositivo, y desde cualquiera de los navegadores web existentes en la actualidad. </p> <h2>Mejoras de esta entrega:</h2> <ul><li>Se ha mejorado la implementacion del Framework Bootstrap.</li><li>Se ha integrado y efectuado una adaptacion del contenido 100% Responsive.</li><li>Acceso a Nuevas Formulas Revision Agosto 2020.</li><li>Hemos simplificado el aplicativo y hemos efectuado una unificacion del Kernel del sistema que ha sido compilado de forma mas simple.</li><li>Gracias a la recodificacion de nuestro Kernel se ha logrado el aumento del 12% en la velocidad de carga del aplicativo desde cualquier dispositivo.</li><li>Mejoras en formulaciones y en la carga de los respectivos conceptos de cada componente de la formula de los productos.</li><li>En cada producto se ha realizado una integracion dinamica del rombo de seguridad.</li><li>Se ha revisado y corregido una amplia mejora en el tratamiento de los plurales y singulares del sistema de clasificacion de elementos.</li><li>Una de las novedades de nuestra ultima actualizacion se refiere a la implementacion de un script de reconocimiento de instalaciones de Modulos, esta sera una nueva herramienta que hemos incluido en esta actualizacion y le permitira acceder a los modulos instalados en el sistema.</li></ul> <div class='clearfix'></div>";

    for (a in secciones){
        contenido += "<h1>" + secciones[a] + "</h1><ol>";

        for (li in producto) {
        if (producto[li].grupo == secciones[a])
        {
              contenido += "<li>"+ producto[li].n + "</li>";
         }
    }
    contenido += "</ol>";
 };
 contenido += "<h2>Realidades aprovechables:</h2> <div class='clearfix'></div>";
 contenido += "<img class='col-xs-12 col-sm-6' src=\"media\/imagen\/general\/venta.jpg\">" +
                " <p>En la actualidad los detergentes se han convertido en uno de los productos químicos de mayor uso diario. Y eso no requerimos ir a una literatura o base de datos especializada para confirmarlo, ya que de nuestra propia experiencia ya que los utilizamos para todo, desde limpiar el piso de nuestros hogares, oficinas, cocinas hasta lavarnos nuestro cuerpo, piel, cabello, dientes etc etc..  A nivel industrial este es un mercado muy competitivo, existe una gran cantidad de marcas de detergentes con diferentes presentaciones ofrecidas a los consumidores. Los productos de limpieza están compuestos por sustancias químicas muy potentes como lo son: <b>Componentes Acidos</b> (que le otorgan el pH, formándolos en ácidos, neutros o alcalinos), <b>Alcalisis o Fosfatos Fuertes</b>, que tienen la propiedad de disolver la suciedad y las impurezas de un material sin corroerlo, adicionalmente segun sea su aplicacion contienen tambien componentes estabilizantes, quelantes, blanqueadores, colorantes, solventes y desinfectantes. A nivel Mundial existen y han existido diversas industrias dedicadas a la fabrica tanto de productos de limpieza como de cuidado personal pudiendo mencionar unas de las mas grandes como: Colgate Palmolive, Procter & Gamble, Johnson & Johnson, Reckitt Benckiser, Unilever, Clorox, entre otras, con el propósito de cubrir las necesidades de la población.</p>" +
                "<img class='col-xs-12 col-sm-6' src=\"media\/imagen\/general\/venta2.jpg\">" +
                " <p>La gran mayoria de productos ofrecidos por estas compañias tienden a ser presentados con diversos niveles de calidad, posiblemente respondiendo a la realidad de comercializar productos a sectores populares y otros de una mayor calidad de accion dirigido al sector de la poblacion que puede pagar la diferencia del valor de comercializacion si lo comparamos con el producto de calidad media o baja vendido o fabricado por la misma compañia, logicamente los costos estan ajustados a la realidad de cada pais donde el mismo se comercializa, pudiendo ser considerado por mucho de nosotros como producto de alto costo para la economia que cada quien maneja y es que existen razones administrativas para que los productos sean comercializados a los precios que se comercializa, sea considerado alto o no su precio y es por ello y aprovechando esa realidad se puede hacer uso de este aplicativo que les permitirá fabricar productos con un costo de produccion que se aproxima al 20% del valor del producto comercial, permitiendo iniciar un negocio a nivel local que porque no proyectarlo a nivel industrial manejando sustanciosas ganancias a la vez que no se dejan de fabricar y comercializar productos de buena calidad a precios atractivos a la economia del consumidor final. </p></div> <div role = 'tabpanel' class = 'tab-pane' id = 'profile'> <h1>COMO USAR ESTE MANUAL</h1> <p>Es bastante practico e intuitivo, tome en cuenta que al ser un manual donde el 98% del codigo fuente esta del lado del cliente es posible que experimente ver textos con simbolos raros, normalmente donde se utilicen letras como Ñ o Palabras Acentuadas, de ser asi, normalmente lo primero que debe hacer es no alarmarse e ir a las configuraciones de su navegador web y cambiar su configuracion de codificacion a <kbd>charset=UTF-8</kbd>si por el contrario usted puede visualizar los caracteres sin errores, entonces solo le queda revisar todas las secciones disponibles en este producto.</p>  <div class = 'panel-group' id = 'accordion' role = 'tablist' aria-multiselectable = 'true'> <div class = 'panel panel-default'> <div class = 'panel-heading' role = 'tab' id = 'headingOne'> <h4 class = 'panel-title'> <a role = 'button' data-toggle = 'collapse' data-parent = '#accordion' href = '#collapseOne' aria-expanded = 'true' aria-controls = 'collapseOne'>PRIMER PASO Seleccionar La Seccion a Revisar</a> </h4> </div> <div id = 'collapseOne' class = 'panel-collapse collapse in' role = 'tabpanel' el sentido aria-labelledby = 'headingOne'> <div class = 'panel-body'> <p>El diseño fue pensado de manera intuitiva, para acceder a cada una de las secciones que se desea revisar solo debe hacer clic en la barra de seleccion de modulos y luego a la barra de seleccion de producto a preparar, asi como esta en el ejemplo de la imagen siguiente:</p> <div align = 'center'> <img src = 'media/imagen/portadas/productos.png' width = '30%' alt = 'COMO EMPEZAR'> </div> </div> </div> </div> <div class = 'panel panel-default'> <div class = 'panel-heading' role = 'tab' id = 'headingTwo'> <h4 class = 'panel-title'> <a class = 'collapsed' role = 'button' data-toggle = 'collapse' data-parent = '#accordion' href = '#collapseTwo' aria-expanded = 'false' aria-controls = 'collapseTwo'>SEGUNDO PASO Revisar el contenido de cada Seccion y Calcular Componentes de la Formula</a> </h4> </div> <div id = 'collapseTwo' class = 'panel-collapse collapse' role = 'tabpanel' aria-labelledby = 'headingTwo'> <div class = 'panel-body'> <p>Luego de seleccionada la Seccion o grupo de Lecciones, puede revisar su contenido, en la seccion de <b>Calculo de Formulas</b> luego de seleccionado el producto a preparar le aparecera un formulario donde debe indicar el numero de litros o kilos de producto que desee preparar, asi como se ejemplifica en la siguiente imagen::</p> <div align = 'center'> <img src = 'media/imagen/portadas/procesar.png' width = '80%' alt = 'COMO EMPEZAR'> </div> <br> </div> </div> </div> <div class = 'panel panel-default'> <div class = 'panel-heading' role = 'tab' id = 'headingThree'> <h4 class = 'panel-title'> <a class = 'collapsed' role = 'button' data-toggle = 'collapse' data-parent = '#accordion' href = '#collapseThree' aria-expanded = 'false' aria-controls = 'collapseThree'>TERCER PASO Imprimir si lo Desea</a> </h4> </div> <div id = 'collapseThree' class = 'panel-collapse collapse' role = 'tabpanel' aria-labelledby = 'headingThree'> <div class = 'panel-body'> <p>Por ultimo usted es libre de imprimir el contenido de los PDF o simplemente consultarlos cuantas veces considere necesario:</p> <div align = 'center'> <img src = 'media/imagen/portadas/imprimir.png' width = '80%' alt = 'COMO EMPEZAR'> </div> <br> </div> </div> </div> </div> </div> <div role = 'tabpanel' class = 'tab-pane' id = 'contacto'> <h1>Puede contactarnos en:</h1> <br> <p class = 'navbar-text pull-left'>Ingresando a nuestro portal web <a href = 'http://www.jesuministrosymas.com.ve' target = '_blank' style = 'color: #0027ff'>J.E SUMINISTROS Y MAS, C.A.</a> </p> <br> <br> <br> <br> <br> <br> <br> <br> </div> <div role = 'tabpanel' class = 'tab-pane' id = 'otros'> <div id = 'carousel-example-generic' class = 'carousel slide' data-ride = 'carousel'> <!-- Indicators --> " +
                "<ol class = 'carousel-indicators'> " +
                "<li data-target = '#carousel-example-generic' data-slide-to = '0' class = 'active'> </li> " +
                "<li data-target = '#carousel-example-generic' data-slide-to = '1'> </li> " +
                "<li data-target = '#carousel-example-generic' data-slide-to = '2'> </li> " +
                "<li data-target = '#carousel-example-generic' data-slide-to = '3'> </li></ol> " +
                "<!-- Wrapper for slides --> " +
                "<div class = 'carousel-inner' role = 'listbox'> " +
                "<div class = 'item active'> <img class = 'img-rounded' src = 'media/imagen/portadas/cursos/merck.png' alt = 'Manual Merck de Medicina Veterinaria...'> " +
                "<div class = 'carousel-caption'> <h3>Manual Merck de Medicina Veterinaria</h3> <p> <a class = 'btn btn-default' href = 'http://www.jesuministrosymas.com.ve/productos/cursos/manual-merck-medicina-veterinaria' target = '_blank' name = 'Bienvenida' id = 'link7' lang = 'es' title = 'Manual Merck de Medicina Veterinaria'role = 'button'>MAS INFORMACION AQUI</a> </p> </div> </div> " +
                "<div class = 'item'> <img class = 'img-rounded' src = 'media/imagen/portadas/cursos/servidor.png' alt = '...'> <div class = 'carousel-caption'> <h3>Monte su Propio Servidor Web en Casa</h3> <p> <a class = 'btn btn-default' href = 'http://www.jesuministrosymas.com.ve/productos/cursos/monta-tu-server' target = '_blank' name = 'Bienvenida' id = 'link8' lang = 'es' title = 'Monte su Propio Servidor Web en Casa'role = 'button'>MAS INFORMACION AQUI</a> </p> </div> </div>"
                +
                "<div class = 'item'> <img class = 'img-rounded' src = 'media/imagen/portadas/cursos/otros.png' alt = '...'> <div class = 'carousel-caption'> <h3>Otros Cursos</h3> <p> <a class = 'btn btn-default' href = 'http://www.jesuministrosymas.com.ve/productos/cursos' target = '_blank' name = 'Bienvenida' id = 'link8' lang = 'es' title = 'Otros Cursos Disponibles'role = 'button'>MAS INFORMACION AQUI</a> </p> </div> </div>" +
                "<div class = 'item'> <img class = 'img-rounded' src = 'media/imagen/portadas/cursos/limpieza.png' alt = '...'> <div class = 'carousel-caption'> <h3>Aprenda a Fabricar Productos de Limpieza</h3> <p> <a class = 'btn btn-default' href = 'http://www.jesuministrosymas.com.ve/productos/cursos/elabore-productos-de-limpieza' target = '_blank' name = 'Bienvenida' id = 'link8' lang = 'es' title = 'Aprenda a Fabricar Productos de Limpieza'role = 'button'>MAS INFORMACION AQUI</a> </p> </div> </div>" +
                "...</div> <!-- Controls --> <a class = 'left carousel-control' href = '#carousel-example-generic' role = 'button' data-slide = 'prev'> <span class = 'glyphicon glyphicon-chevron-left' aria-hidden = 'true'> </span> <span class = 'sr-only'>Previous</span> </a> <a class = 'right carousel-control' href = '#carousel-example-generic' role = 'button' data-slide = 'next'> <span class = 'glyphicon glyphicon-chevron-right' aria-hidden = 'true'> </span> <span class = 'sr-only'>Next</span> </a> </div> </div> </div> </div> </div> </div> </div> <footer> <div class = 'navbar navbar-default '> <div class = 'container'> <p> <b>Nota</b>: El aprendizaje depende de usted, los humanos solo debemos tener un motivo para hacer las cosas, nada es imposible en esta vida, todo se logra cuando se quiere..!</p> </div> </div> </footer>";

    document.getElementById('contenido').innerHTML = contenido;
}



function creditos(){
    resultado = "";
    resultado += "<div class=\"panel panel-info\">";
    resultado += "<div class=\"panel-heading\">";
    resultado += "<h3 class=\"panel-title\"><b>Creditos, Web y Libros Consultados</b><\/h3>";
    resultado += "<\/div>";
    resultado += "<div class=\"panel-body\">";
    resultado += imprimirxs;

    resultado += "<h2 class=\"western\">Libros Consultados<\/h2>";
    resultado += "<ul>";
    resultado += "<li>";
    resultado += "<p>Proyecto “Apoyo al Fortalecimiento de las Capacidades en Manejo Ambiental en las Américas”, (2007) Informe General, DDS.<\/p>";
    resultado += "<\/li>";
    resultado += "<li>";
    resultado += "<p>Ullmann, F. (1931). Enciclopedia de química industrial.<\/p>";
    resultado += "<\/li>";
    resultado += "<li>";
    resultado += "<p>Salager, J. L., & Fernández, A. (2004). Surfactantes. Cuaderno FIRP, 300, 07-13.<\/p>";
    resultado += "<\/li>";
    resultado += "<li>";
    resultado += "<p>García, G., & Cruz, L. (2011). Elaboración de cuatro tipos de jabones utilizando aceites vegetales residuales de palma africana (Elaeis guineensis) mediante el método de saponificación. Graduated (Doctoral dissertation, Thesis. Universidad";
    resultado += " Estatal de Bolívar. Facultad de Ciencias Agropecuarias. Recursos Naturales y del Ambiente).<\/p>";
    resultado += "<\/li>";
    resultado += "<li>";
    resultado += "<p>Yubaille, M., Proaño, F., & Peñafiel, S. (2013). Evaluación de tres métodos de saponificación de grasas destinadas a la alimentacion de vacas lecheras (Doctoral dissertation, Tesis Ingenieria en Industrias Pecuarias. Riobamba: Escuela Superior";
    resultado += " Politécnica del Chimborazo, Facultad de Ciencias Pecuarias).<\/p>";
    resultado += "<\/li>";
    resultado += "<li>";
    resultado += "<p>Moraga, L., Molina, G., Agüero, H., & Zurita, L. (1987). Empleo del Lauril Sulfato de Sodio para el diagnostico de mastitis subclínica. XII Reunión Anual Sociedad Chilena de Producción Animal. Santiago, CL, 46.<\/p>";
    resultado += "<\/li>";
    resultado += "";
    resultado += "<\/ul>";




    var webvisitadas = [{
            nombre: "Adobe Acrobat Reader DC",
            direccion: "https://get.adobe.com/reader/?loc=es",
            fecha: "22-08-2020"
        }, {
            nombre: "Apache",
            direccion: "http://www.apache.org",
            fecha: "22-08-2020"
        }, {
            nombre: "Curiosoando",
            direccion: "https://curiosoando.com/",
            fecha: "22-08-2020"
        }, {
            nombre: "PNI de Uruguay",
            direccion: "http://www.nip.gub.uy/",
            fecha: "22-08-2020"
        }, {
            nombre: "Apache HTTP Server Project",
            direccion: "http://httpd.apache.org/",
            fecha: "22-08-2020"
        }, {
            nombre: "Apache Lounge",
            direccion: "http://www.apachelounge.com/download/",
            fecha: "22-08-2020"
        }, {
            nombre: "ApacheHaus",
            direccion: "http://www.apachehaus.com/cgi-bin/download.plx",
            fecha: "22-08-2020"
        }, {
            nombre: "Aulas en red, aplicaciones y servicios.Linux Permisos de archivos y carpetas",
            direccion: "http://goo.gl/9JeDdt",
            fecha: "22-08-2020"
        }, {
            nombre: "BitNami WAMP Stack",
            direccion: "https://bitnami.com/stack/wamp",
            fecha: "22-08-2020"
        }, {
            nombre: "Bluefish",
            direccion: "http://bluefish.openoffice.nl/index.html",
            fecha: "22-08-2020"
        }, {
            nombre: "CANAIMA",
            direccion: "http://canaima.softwarelibre.gob.ve/",
            fecha: "22-08-2020"
        }, {
            nombre: "Centos The CentOS Project",
            direccion: "https://www.centos.org/",
            fecha: "22-08-2020"
        }, {
            nombre: "Chrome",
            direccion: "https://www.google.es/chrome/browser/desktop/",
            fecha: "22-08-2020"
        }, {
            nombre: "DEBIAN",
            direccion: "https://www.debian.org/index.es.html",
            fecha: "22-08-2020"
        }, {
            nombre: "Evince",
            direccion: "https://wiki.gnome.org/Apps/Evince",
            fecha: "22-08-2020"
        }, {
            nombre: "FileZilla",
            direccion: "https://filezilla-project.org/",
            fecha: "22-08-2020"
        }, {
            nombre: "Foxit Reader 7",
            direccion: "http://www.foxitsoftware.com/spanish/products/reader/",
            fecha: "22-08-2020"
        }, {
            nombre: "LibreOffice suite de oficina",
            direccion: "https://es.libreoffice.org/",
            fecha: "22-08-2020"
        }, {
            nombre: "Páginas web con PHP",
            direccion: "http://www.mclibre.org/",
            fecha: "22-08-2020"
        }, {
            nombre: "Tightvnc",
            direccion: " www.tightvnc.com",
            fecha: "22-08-2020"
        }, {
            nombre: "UBUNTU",
            direccion: "http://www.ubuntu.com",
            fecha: "22-08-2020"
        }, {
            nombre: "WAMPP",
            direccion: "https://www.apachefriends.org/es/",
            fecha: "22-08-2020"
        }, {
            nombre: "WampServer",
            direccion: "http://www.wampserver.es/",
            fecha: "22-08-2020"
        }, {
            nombre: "JavaScript",
            direccion: "https://www.javascript.com/",
            fecha: "22-08-2020"
        }, {
            nombre: "JQUERY",
            direccion: "https://jquery.com/",
            fecha: "22-08-2020"
        }, {
            nombre: "Kernel Linux",
            direccion: "https://www.kernel.org/",
            fecha: "22-08-2020"
        }, {
            nombre: "JavaScript (JS)",
            direccion: "https://developer.mozilla.org/es/docs/Web/JavaScript",
            fecha: "22-08-2020"
        }, {
            nombre: "Wikipedia",
            direccion: "https://es.wikipedia.org/",
            fecha: "22-08-2020"
        }, {
            nombre: "Neutralizacion de Acidos y Bases",
            direccion: "http://www.ehu.eus/biomoleculas/ph/neutra.htm",
            fecha: "22-08-2020"
        }, {
            nombre: "Stackoverflow",
            direccion: "http://es.stackoverflow.com",
            fecha: "22-08-2020"
    }];
    // Ordena de manera alfabetica
    webvisitadas.sort(function (a, b) {
        if (a.nombre > b.nombre) {
            return 1;
        }
        if (a.nombre < b.nombre) {
            return -1;
        }
        // a must be equal to b
        return 0;
    });
    // Genera los link y lista de webvisitadas
    disponible = "<b> Disponible en: </b>";
    fecha_consulta = "<b> Fecha de Consulta: </b>";
    resultado += "<h2 class='western'>Web Visitadas</h2>";
    resultado += "<ul>";

    for (key in webvisitadas) {
        var nombre = webvisitadas[key];
        var direccion = webvisitadas[key];
        var fecha = webvisitadas[key];
        var listaPalabra = nombre.nombre;
        var listaParrafo = direccion.direccion;
        var listaFecha = fecha.fecha;
        var listaA = listaPalabra.toUpperCase();
        var listaB = "<a title='Visitar " + listaPalabra + "' class='clase6' href='" + listaParrafo + "' target = '_blank'>" + listaParrafo + "</a>";
        // Genera el contenido que va a html contenido
        resultado += "<li>" + listaA + "<br>" + disponible + listaB + "<br>" + fecha_consulta + listaFecha + "<br><br></li>";
    }

    resultado += "</ul>";
    resultado += "<a title=\"Visitar Creative Commons\" target=\"_blank\" rel=\"license\" href=\"http:\/\/creativecommons.org\/licenses\/by-sa\/3.0\/ve\/\">";
    resultado += "<img alt=\"Licencia de Creative Commons\" style=\"border-width:0\" src=\"media\/imagen\/88x31.png\"><\/a>";
    resultado += "<br\/>Este obra esta bajo una <a title=\"Visitar Licencia Creative Commons\" class=\"clase6\" rel=\"license\" target=\"_blank\" href=\"http:\/\/creativecommons.org\/licenses\/by-sa\/3.0\/ve\/\"> licencia de Creative Commons<\/a>";
    resultado += "<br>";
    resultado += "<p align=\"justify\">Usted puede vender, distribuir, re-distribuir, cambiar, adaptar, modificar este producto, pero siempre debe indicar de quien es el diseño original y cuales fueron las modificaciones efectuadas, si desean que se incluya alguna mejora pueden";
    resultado += "comunicarse con J.E Suministros y Mas, C.A. en";
    resultado += "<a class=\"clase6\" href=\"http:\/\/www.jesuministrosymas.com.ve\/\" name=\"Ingresar a la web de J.E SUMINISTROS Y MAS, C.A.\" target=\"_blank\">www.jesuministrosymas.com.ve<\/a><\/p><br>";
    resultado += "<p class=\"bg-warning\">";
    resultado += "<\/b> Diseñado y Producido en:<br> La República Bolivariana de Venezuela<br> Maracay - Estado Aragua<br> Desde el Año 2016<br>Version Actual "+version+" Del año "+ano+"<br>Diseño Bajo Tecnologías Libres.<br> Autor y Diseño: José Herrera<br> Revisión: Ing. Elena Nuñez<br>        Esta fue una Producción de <a class=\"clase6\" href=\"http:\/\/www.jesuministrosymas.com.ve\/\" name=\"J.E SUMINISTROS Y MAS, C.A.\" target=\"_blank\">J.E SUMINISTROS Y MAS, C.A.<\/a>";
    resultado += "<\/p>";
    resultado += "<h2>PARA CITAR ESTE MANUAL:<\/h2>";
    resultado += "";
    resultado += "";
    resultado += "<div>";
    resultado += "<!-- Nav tabs -->";
    resultado += "<ul class=\"nav nav-tabs\" role=\"tablist\">";
    resultado += "<li role=\"presentation\" class=\"active\"><a href=\"#apa\" aria-controls=\"apae\" role=\"tab\" data-toggle=\"tab\">NORMA APA<\/a><\/li>";
    resultado += "<li role=\"presentation\"><a href=\"#iso0690\" aria-controls=\"iso0690\" role=\"tab\" data-toggle=\"tab\">NORMA ISO0690<\/a><\/li>";
    resultado += "<li role=\"presentation\"><a href=\"#mla\" aria-controls=\"mla\" role=\"tab\" data-toggle=\"tab\">NORMA MLA<\/a><\/li>";
    resultado += "<\/ul>";
    resultado += "<!-- Tab panes -->";
    resultado += "<div class=\"tab-content\">";
    resultado += "<div role=\"tabpanel\" class=\"tab-pane active fade in active\" id=\"apa\">";
    resultado += "<h3>NORMA APA<\/h3>";
    resultado += "Herrera Vargas J. M. ("+ano+"). MANUAL DIGITAL COMO ELABORAR PRODUCTOS DE LIMPIEZA EN VENEZUELA VERSION "+version+" EDICIONES J.E SUMINISTROS Y MAS, C.A.";
    resultado += "<\/div>";
    resultado += "<div role=\"tabpanel\" class=\"tab-pane fade\" id=\"iso0690\">";
    resultado += "<h3>NORMA ISO690<\/h3>";
    resultado += "HERRERA VARGAS, Jose Medardo; J.E SUMINISTROS Y MAS, C.A., MANUAL DIGITAL COMO ELABORAR PRODUCTOS DE LIMPIEZA EN VENEZUELA VERSION  "+version+" . "+ano+".";
    resultado += "<\/div>";
    resultado += "<div role=\"tabpanel\" class=\"tab-pane fade\" id=\"mla\">";
    resultado += "<h3>NORMA MLA<\/h3>";
    resultado += "Herrera Vargas, Jose Medardo. \"MANUAL DIGITAL COMO ELABORAR PRODUCTOS DE LIMPIEZA EN VENEZUELA VERSION "+version+"\" EDICIONES J.E SUMINISTROS Y MAS, C.A. ("+ano+").";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";

    document.getElementById('contenido').innerHTMLet = resultado;
}


function nfpa(){
    var resultado = "";
    resultado += "";
    resultado += "<div class=\"panel panel-info\">";
    resultado += "<div class=\"panel-heading\"><strong>Rombo NFPA</strong><\/div>";
    resultado += "<div class=\"panel-body\">";
    resultado += imprimirxs;
    resultado += "<p>El rombo NFPA, también conocido como diamante NFPA, es un diagrama que contiene información sobre los riesgos asociados a";
    resultado += " una determinada sustancia según la norma 704 del National Fire Protection Association (NFPA) de Estados Unidos. El diagrama";
    resultado += " consiste en un código de colores y números muy sencillo que tiene como objetivo que se puedan identificar rápidamente";
    resultado += " los peligros de un material en situaciones de emergencia. En inglés es más conocido como diamond of fire o diamond of";
    resultado += " hazardous materials (diamante de los materiales peligrosos).<\/p>";
    resultado += " <div class=\"text-center\">";
    resultado += " <h1>Interpretación de la norma NFPA 704<\/h1>";
    resultado += " <img width=\"60%\" src=\"media\/imagen\/general\/rombo_nfpa_704.png\" alt=\"\">";
    resultado += " <\/div>";
    resultado += " <p>El rombo de los materiales peligrosos está dividido en cuatro secciones. Cada sección tiene un color distinto y un número.";
    resultado += " El color se asocia con diferentes tipos de peligros. Azul para salud, rojo para inflamabilidad, amarillo para reactividad";
    resultado += " e inestabilidad y blanco para peligrosos específicos de algunos materiales, por ejemplo si es radioactivo o si proviene";
    resultado += " de muestras biológicas.<\/p>";
    resultado += " <p> El número de cada sección puede ir desde cero si no hay peligro hasta 4 para riesgo máximo. Por ejemplo, un cero en la sección";
    resultado += " azul indicaría que la sustancia no presenta ningún riesgo para la salud mientras que el 4 indica que puede ser mortal";
    resultado += " con una exposición corta.<\/p>";
    resultado += " <div class=\"alert alert-info\" role=\"alert\">";
    resultado += " <h3> Azul: riesgo para la salud<\/h3>";
    resultado += " <ul>";
    resultado += " <li>";
    resultado += " <strong> Nivel 0:<\/strong> la exposición al material no presenta riesgos para la salud más allá de los que presenta cualquier";
    resultado += " material inflamable ordinario, por ejemplo la madera. No hay riesgos incluso en casos de ingestión o inhalación";
    resultado += " de grandes cantidades, por ejemplo el cloruro de sodio (sal común). <\/li>";
    resultado += " <li>";
    resultado += " <strong> Nivel 1:<\/strong> la exposición al material puede causar irritación y otros daños menores no persistentes incluso";
    resultado += " en ausencia de asistencia médica. Por ejemplo, la acetona o el bromato de sodio. <\/li>";
    resultado += " <li>";
    resultado += " <strong> Nivel 2:<\/strong> la exposición intensa, o prolongada pero sin ser crónica, puede causar irritación y daños residuales";
    resultado += " con incapacitación temporal o permanente si no hay atención médica rápida. Por ejemplo, el éter dietílico o el";
    resultado += " cloroformo. <\/li>";
    resultado += " <li>";
    resultado += " <strong> Nivel 3:<\/strong> la exposición aguada durante poco tiempo puede causar daños serios temporales o daños residuales";
    resultado += " moderados incluso con atención médica inmediata. Por ejemplo, el cloro, hidróxido sódico o monóxido de carbono.";
    resultado += " <\/li>";
    resultado += " <li>";
    resultado += " <strong> Nivel 4:<\/strong> exposiciones muy cortas pueden resultar letales o causar daños graves permanentes. Por ejemplo,";
    resultado += " cianuro, fosfano o ácido fluorhídrico.<\/li>";
    resultado += " <\/ul>";
    resultado += " <\/div>";
    resultado += " ";
    resultado += " <div class=\"alert alert-danger\" role=\"alert\">";
    resultado += " <h3>Rojo: inflamabilidad<\/h3>";
    resultado += " <ul>";
    resultado += " <li>";
    resultado += " <strong> Nivel 0: <\/strong> materiales no inflamables en condiciones normales, por ejemplo el tetracloruro de carbono.";
    resultado += " Se incluyen los materiales intrínsecamente no inflamables, por ejemplo cemento, que son aquellos que no se inflaman";
    resultado += " si se exponen al aire y se calientan hasta los 820 ºC durante cinco minutos. <\/li>";
    resultado += " <li>";
    resultado += " <strong> Nivel 1: <\/strong> materiales con un punto de inflamabilidad de 93 ºC o superior. Por ejemplo la mayoría de aceites";
    resultado += " minerales. No entran en ignición en condiciones ambientales sin que haya precalentamiento.<\/li>";
    resultado += " <li>";
    resultado += " <strong> Nivel 2: <\/strong> punto de inflamabilidad entre 38 y 93ºC. Por ejemplo, el gasóleo. La ignición de estos materiales";
    resultado += " puede ocurrir si se exponen a temperaturas ambientales relativamente altas sin necesidad de calentamiento.<\/li>";
    resultado += " <li>";
    resultado += " <strong> Nivel 3: <\/strong> punto de inflamabilidad entre 23 ºC y 38 ºC; también se incluyen líquidos con punto de inflamabilidad";
    resultado += " por debajo de 23 ºC y punto de ebullición igual o superior a 38 ºC. Estos materiales pueden arder bajo la mayoría";
    resultado += " de condiciones ambientales. Por ejemplo, acetona.<\/li>";
    resultado += " <li>";
    resultado += " <strong> Nivel 4: <\/strong> punto de inflamabilidad por debajo de 23 ºC. Son líquidos que arden rápidamente y que a presión";
    resultado += " atmosférica y temperatura normal se vaporizan rápidamente dispersándose por el aire. Por ejemplo, acetileno,";
    resultado += " hidrógeno líquido o sustancias pirofosfóricas.<\/li>";
    resultado += " <\/ul>";
    resultado += " ";
    resultado += " <\/div>";
    resultado += " ";
    resultado += " <div class=\"alert alert-warning\" role=\"alert\">";
    resultado += " <h3>Amarillo: reactividad\/inestabilidad<\/h3>";
    resultado += " <ul>";
    resultado += " <li>";
    resultado += " <strong>Nivel 0:<\/strong> materiales normalmente estables incluso cuándo son expuestos al fuego y que no reaccionan con";
    resultado += " el agua. Por ejemplo, el helio.<\/li>";
    resultado += " <li>";
    resultado += " <strong>Nivel 1:<\/strong> materiales normalmente estables pero que pueden volverse inestables ante condiciones de elevada";
    resultado += " presión y temperatura. Por ejemplo, el propileno y el acetileno. <\/li>";
    resultado += " <li>";
    resultado += " <strong>Nivel 2:<\/strong> materiales que normalmente son estables pero que pueden sufrir cambios químicos bruscos a temperatura";
    resultado += " y presión elevada. También se incluyen materiales que reaccionan de forma violenta con el agua. Por ejemplo,";
    resultado += " el potasio, el sodio y algunos compuestos derivados como la sosa cáustica. <\/li>";
    resultado += " <li>";
    resultado += " <strong>Nivel 3:<\/strong> materiales que pueden detonar si se exponen a algún agente que inicie la reacción tales como";
    resultado += " una fuente de ignición, calentamiento previo o un impacto fuerte. Se incluyen materiales que reaccionan de forma";
    resultado += " violenta con el agua o que pueden explotar si reciben una descarga eléctrica. Por ejemplo, nitrato amónico, trifloruro";
    resultado += " de cloro o el flúor.<\/li>";
    resultado += " <li>";
    resultado += " <strong>Nivel 4:<\/strong> materiales susceptibles de detonación o descomposición explosiva bajo condiciones normales";
    resultado += " de presión y temperatura. Por ejemplo, nitroglicerina, dióxido de cloro o azida de sodio.<\/li>";
    resultado += " <\/ul>";
    resultado += " ";
    resultado += " <\/div>";
    resultado += " ";
    resultado += " <div class=\"well\">";
    resultado += " <h3>Blanco: riesgos específicos y avisos especiales<\/h3>";
    resultado += " <p> En la sección blanca del diamante de los materiales peligrosos se incluyen letras y símbolos especiales que indican riesgos";
    resultado += " específicos. <\/p>";
    resultado += " <ul>";
    resultado += " <li>";
    resultado += " <strong>OX:<\/strong> material muy oxidante que puede provocar la combustión de otros materiales sin presencia de aire.";
    resultado += " A veces se puede ver como OXY aunque el estándar NFPA 704 no recoge estas siglas. Por ejemplo, el perclorato";
    resultado += " potásico y el peróxido de hidrógeno.<\/li>";
    resultado += " <li>";
    resultado += " <strong>W:<\/strong> material peligroso en contacto con agua. Por ejemplo, el celsio o el ácido sulfúrico.<\/li>";
    resultado += " <li>";
    resultado += " <strong>SA:<\/strong> con las siglas de Simple Asphyxiant gas, en español gas asfixiante simple. Es específico para los";
    resultado += " gases hidrógeno, nitrógeno, helio, neón, argón, kryptón y xenón. <\/li>";
    resultado += " <\/ul>";
    resultado += " <p>";
    resultado += " Las siguientes siglas y símbolos no están recogidas en el estándar NFPA 704 pero son utilizados con relativa frecuencia:";
    resultado += " <\/p>";
    resultado += " <ul>";
    resultado += " <li>";
    resultado += " <strong>COR o CORR: <\/strong> materiales corrosivos, generalmente ácidos y bases fuertes.<\/li>";
    resultado += " <li>";
    resultado += " <strong>ACID: <\/strong> ácidos fuertes. Por ejemplo, ácido sulfúrico.<\/li>";
    resultado += " <li>";
    resultado += " <strong>ALK: <\/strong> bases (álcalis) fuertes. Por ejemplo, hidróxido potásico.";
    resultado += " <li>";
    resultado += " <strong>BIO o Símbolo de riesgo biológico:";
    resultado += " <img src=\"media\/imagen\/general\/24px_simbolo_riesgo_biologico.png\" alt=\"\"> <\/strong> material biológico o con residuos biológicos que suponen riesgo de transmisión de patógenos.";
    resultado += " Por ejemplo, material hospitalario y cultivos bacterianos.<\/li>";
    resultado += " <li>";
    resultado += " <strong>RA, RAD o Símbolo de riesgo radiactivo:";
    resultado += " <img src=\"media\/imagen\/general\/24px_simbolo_riesgo_radiactivo.png\"";
    resultado += " alt=\"\"> <\/strong> material radiactivo. Por ejemplo, el plutonio y el uranio.<\/li>";
    resultado += " <li>";
    resultado += " <strong>CYL o CRYO: <\/strong> material criogénico. Por ejemplo, nitrógeno líquido. <\/li>";
    resultado += "<li><strong>POI:</strong> sustancias venenosas (del";
    resultado += " inglés poison). Por ejemplo, arsénico y estricnina.<\/li>";
    resultado += " <\/ul>";
    resultado += " <\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";


     document.getElementById('contenido').innerHTML = resultado;

}

function glosario(){

    var glosario = [
        {
            palabra: "",
            parrafo: ""
        }, {
            palabra: "Malva",
            parrafo: "La malva común (Malva sylvestris) es una planta herbácea de la familia de las malváceas, muy abundante en toda Europa en bordes de caminos y en terrenos tanto cultivados como no cultivados."
        }, {
            palabra: "Acido Estearico",
            parrafo: "El ácido esteárico es un ácido graso saturado de 18 átomos de carbono presente en aceites y grasas animales y vegetales. A temperatura ambiente es un sólido parecido a la cera; su fórmula química es CH<sub>3</sub>(CH<sub>2</sub>)16COOH.Su nombre IUPAC es ácido octadecanoico.Tiene una cadena hidrofóbica de carbono e hidrógeno. Se obtiene tratando la grasa animal con agua a una alta presión y temperatura, y mediante la hidrogenación de los aceites vegetales.Algunas de sus sales, principalmente de sodio y potasio, tienen propiedades como tensoactivas.Es muy usado en la fabricación de velas, jabones y cosméticos."
        }, {
            palabra: "Triclosán",
            parrafo: "El triclosán es un potente agente antibacteriano y fungicida. En condiciones normales se trata de un sólido incoloro con un ligero olor a fenol. En caso de ser ingerido, puede llegar a causar enfermedades graves e incluso la muerte, dependiendo la cantidad de la misma que entre a la boca."
        }, {
            palabra: "Alumbre",
            parrafo: "Se conoce como alumbre a un tipo de sulfato triple compuesto por el sulfato de un metal trivalente, como el aluminio, y otro de un metal monovalente. También se pueden crear dos soluciones: una solución saturada en caliente y una solución saturada en frío. Generalmente se refiere al alumbre potásico KAl(SO4)2·12H2O (o a su equivalente natural, la calinita). Una característica destacable de los alumbres es que son equimoleculares, porque por cada molécula de sulfato de aluminio hay una molécula de sulfato del otro metal, y cristalizan hidratados con 12 moléculas de agua en un sistema cúbico.Se utiliza en las valoraciones argentométricas, específicamente en el método de Volhard(véase Jacob Volhard) para la determinación de haluros como cloruros."
        }, {
            palabra: "Clorhidrao de Aluminio",
            parrafo: "El clorhidrato de aluminio es el ingrediente químico más presente en los desodorantes comerciales. Es casi imposible encontrar uno que no contenga derivados del aluminio en perfumerías o supermercados, a no ser alguna gama especial de ciertas marcas comerciales."
        },{
            palabra: "Tween 20 - Polisorbato 20",
            parrafo: "El polisorbato 20 o monolaurato de polioxietilen(20)sorbitano, conocido comercialmente como Tween 20, es un tensoactivo tipo polisorbato cuya estabilidad y relativa ausencia de toxicidad permiten que sea usado como detergente y emulsionante en numerosas aplicaciones domésticas, científicas, alimentarias, industriales y farmacológicas. Es un aditivo alimentario aprobado por la Unión Europea, para uso en Alimentos e identificada como E 432."
        },{
            palabra: "Aceite de Lanonina",
            parrafo: "La lanolina es una cera natural producida por las glándulas sebáceas de algunos mamíferos, especialmente del ganado ovino, preparada y que se aplica para diversos usos industriales, farmacéuticos y domésticos."
       },{
            palabra: "Butilhidroxianisol (BHA)",
            parrafo: "El hidroxibutilanisol (BHA por sus siglas en inglés) es una mezcla de dos isómeros de compuestos orgánicos, 2-tert-butil-4-hidroxianisol y 3-tert-butil-4-hidroxianisol. Se prepara a partir de 4-metoxifenol e isobutileno. Es un sólido ceroso que exhibe propiedades antioxidantes. Del mismo modo que el hidroxibutiltolueno(BHT), el anillo aromático conjugado del BHA es capaz de estabilizar a un radical libre, secuestrándolo.Al actuar como un agente secuestrante, se evitan posteriores reacciones de radicales libres."
        },{
            palabra: "Cera Candelilla",
            parrafo: "La cera proviene de la planta Euphorbia antisyphilitica Zucc o Euphorbia Cerífera también conocida como planta de candelilla, la planta presenta características muy similares a las de un catos, es dura y quebradiza. Sin refinar la cera tiene un aspecto opaco."
        },{
            palabra: "Lauril éter sulfato sódico 70 - Texapon N 70",
            parrafo: "Este palabra presenta una notable insensibilidad frente a los endurecedores del agua e incluso a bajas temperaturas es capaz de desarrollar todo su poder espumante.Su alta compatibilidad con la piel y su capacidad humectante y emulsionante, hacen que sea una de las materias primas más usadas en cosmética. A estas propiedades hay que sumarle su ligero olor que permite que sea perfumado sin ningún problema. En cuanto a su coloración, si se presenta en forma de pasta es levemente turbio, pero una vez diluido toma un aspecto claro más o menos transparente según el grado de impurezas; esto permite que también sea fácilmente coloreado."
        }, {
            palabra: "Comperland KD",
            parrafo: "(Los nombres comerciales de este palabra son: Cocoamida, Genamid). Su nombre científico vizcozante de Betaine este palabra le brinda el 30% de espesor al shampoo."
        }, {
            palabra: "Metil parabeno puro",
            parrafo: "(Su nombre comercial propil parabeno puro, su nombre científico Metil Puro). Este producto hace que el shampoo se preserve por más tiempo."
        }, {
            palabra: "Metil-parabeno sódico",
            parrafo: "(Su nombre comercial y científico es el mismo). Este producto combate las bacterias del agua quedando el agua purificada y libre de gérmenes."
        }, {
            palabra: "Cloruro de Sodio",
            parrafo: "Este producto es conocido normalmente como Sal Comun, Sal de Mar, el mismo que se utiliza en la cocina, este producto le brinda el 70% de espesor al shampoo."
        }, {
            palabra: "Acido Cítrico",
            parrafo: "Su nombre comercial y científico es el mismo,este producto es sacado de los cítricos es el que regula el Ph o el grado del shampoo."
        }, {
            palabra: "Acido Bórico",
            parrafo: "Su nombre comercial y científico es el mismo, actua en el shampoo y en el cuero cabelludo como antibacterial."
        }, {
            palabra: "Zinc pirinitione",
            parrafo: "Su nombre comercial piritionato de zinc y su nombre científico es el mismo. Este producto es el que ayuda a controlar la caspa en el cuero cabelludo obviamente anexado al shampoo."
        }, {
            palabra: "Tensoactivo Aniónico",
            parrafo: "Conocido también en el mercado como tensoactivo, anfotero su nombre científico es el mismo. Este producto es el que le brinda el brillo al cabello obviamente anexado al shampoo."
        }, {
            palabra: "Emoliente Natural",
            parrafo: "Comercialmente conocido como plautaren su nombre científico Elastina. Actúa como acondicionador y a la vez es reconstituyente en la raíz del cuero cabelludo anexado en shampoo."
        }, {
            palabra: "Genamin Acondicionante",
            parrafo: "Su nombre comercial es este mismo, su nombre científico Cetilrimetilamonioluoumro, este producto hace que el shampoo quede dos en uno."
        }, {
            palabra: "Glicerina",
            parrafo: "Su nombre comercial y científico es el mismo, actúa en el shampoo como anticaspa en producto como en la crema de manos, cremas para la cara, actúa como humectante e hidratante."
        }, {
            palabra: "Acido Ascórbico",
            parrafo: "Su nombre comercial es vitamina C., actúa como preservativo en productos cosméticos y en productos comestibles stales como la leche condensada, compotas, leche líquida, etc."
        }, {
            palabra: "Complejo Pro Vitamínico B 5",
            parrafo: "Conocido también como Pro-vitamina B-5. Este producto es el que fortalece el cabello desde la raíz hasta la punta. También se puede usar el aceite solo en el cuero cabelludo."
        }, {
            palabra: "Vitamina E.",
            parrafo: "Conocido en el mercado como aceite de germen de trigo. Este producto actúa en el shampoo y en el cuero cabelludo como reconstituyente de folículos pilosos o sea que combate o detiene la caída del cabello. También es utilizado en las cremas para la cara y cremas de mano, y previene las arrugas."
        }, {
            palabra: "Dimeticona",
            parrafo: "Conocido también en el mercado como Enperland Pk 3.000 le brinda al shampoo Pantene el color y le da viscosidad."
        }, {
            palabra: "Texapon Nacarado",
            parrafo: "Su nombre comercial Enperland Nacarante, le brinda al shampoo brillo y lo deja nacarado."
        }, {
            palabra: "Dipanstenol",
            parrafo: "Conocido también el mercado como Pantenol, su consistencia espeso, transparente o emulsionado es el que le brinda la fragancia al shampoo Pantene."
        }, {
            palabra: "Cocoamida",
            parrafo: "Conocido también en el mercado como Dietanolamina o Acido graso de coco. Su nombre científico Nalón Iónico, este producto actúa como vizcozante en el shampoo H y S."
        }, {
            palabra: "Secuestrante",
            parrafo: "Conocido en el mercado como policuart H-80, este producto actúa como acondicionante en el cabello y a la vez fortalece el tejido conjuntivo del cuero cabelludo."
        }, {
            palabra: "Placentafil",
            parrafo: "Este producto es conocido en el mercado como la Vitamina B-6, viene líquida y en polvo. Evita la caída del cabello y lo aumenta obviamente anexado en el shampoo."
        }, {
            palabra: "D-Ycuar-A",
            parrafo: "Este producto actúa en el shampoo como reconstituyente e hidratante para los folículos capilares del cuero cabelludo. Su nombre científico y comercial es el mismo."
        }, {
            palabra: "Genapol",
            parrafo: "Su nombre comercial Texapon 40 su nombre científico Laurilsulftato de Amonio líquido. Es utilizado en el shampoo profesional como espumante, también sirve para utilizarlos en los desinfectantes para pisos."
        }, {
            palabra: "Cocamidopropil –Betaina",
            parrafo: "Este es su nombre científico comercialmente, se conoce como Anfótero, es utilizado en la fabricación del shampoo como limpiador concentrado del cuero cabelludo, combatiendo a la vez algunas bacterias acumuladas en el cabello."
        }, {
            palabra: "Carbocernetilcelulosa CMC",
            parrafo: "Este su nombre científico su nombre comercial CMC, viene en tres referencias CMC especial, CMC extra, CMC corriente. Actúa en los desinfectantes para pisos como concentrante y espesante."
        }, {
            palabra: "Etilenodiaminatetraacetato",
            parrafo: "Este su nombre científico conocido en el mercado como Edta, este producto actúa como bactericida en el shampoo, es suavizante en el cabello y también se le agrega a los desinfectantes para pisos."
        }, {
            palabra: "Dodigen 226",
            parrafo: "Su nombre científico Cloruro de Benzalconio al 50%, es un agente desinfectante a base de sales de amonio cuaternario, diseñado para el control de bacterias algas, hongos, virus como moquillo, viruela, hepatitis, etc. Obviamente anexado en los desinfectantes de alta concentración para pisos. Es muy comercial el desinfectante con este producto en hospitales y centros institucionales."
        }, {
            palabra: "Arcopal N-100",
            parrafo: "Su nombre científico Etermonilfenol-poliglicolico de 10 moles. Es utilizado en la fabricación de productos que llevan agua y aceites o derivados del petróleo, ayuda a que el agua sea compatible con los productos antes nombrados."
        }, {
            palabra: "Detersin K",
            parrafo: "Su nombre comercial jabón líquido K de alta concentración, éste también es su nombre científico. Es un detergente aniónico líquido con fuerza cinergética, excelente para remover la mugre y la grasa más pegada en pisos, paredes y utensilios de cocinas. Es desinfectante y es la base prima para la fabricación de fabuloso."
        }, {
            palabra: "Carbonato de Sodio",
            parrafo: "Su nombre científico soda ASH, este producto es el ingrediente principal para la fabricación del Hiposodio, soluble en agua únicamente, se utiliza en la fabricación de jabones, detergentes, tratamientos de piscinas, etc."
        }, {
            palabra: "Cera Licowax",
            parrafo: "También es conocida en el mercado como cera Erwax, se utiliza en la fabricación de Cera emulsionable y de la cera roja autobrillante."
        }, {
            palabra: "Mergal Líquido",
            parrafo: "Este su nombre comercial y científico. Este producto conservante técnico para productos como pinturas, adhesivos, detergentes, ceras, ambientadores, desinfectantes, etc."
        }, {
            palabra: "Nuvan 50 EC.",
            parrafo: "Este su nombre comercial y científico utilizado en la fabricación de insecticidas y productos veterinarios para el control de garrapatas, pulgas, piojos, hormigas, cucarrones, polillas, moscas, etc."
        }, {
            palabra: "Propilenglicol",
            parrafo: "Su nombre comercial y científico es el mismo, su consistencia líquida incolora e inolora, soluble en agua y en alcohol, se utiliza en la fabricación de productos cosméticos tales como cremas de manos, lociones, productos farmacéuticos, etc."
        }, {
            palabra: "Soda Cáustica en escama",
            parrafo: "Su nombre científico NAOH, Hidróxido de sodio. Utilizado en la fabricación del cloros, jabón de lavar, detergentes, etc. Producto de alto riesgo, manejarlo con cuidado."
        }, {
            palabra: "Hipoclorito de Sodio",
            parrafo: "Conocido también en el mercado como Hiposodio, utilizado en la fabricación de límpido es la materia prima para este producto. No exponerlos a los rayos solares."
        }, {
            palabra: "Oxido de Zinc Medicado",
            parrafo: "Su nombre comercial y científico es el mismo utilizado en la fabricación del Talco Mexana y otros talcos de usos medicinal, combate los hongos, y bacterias irritantes en el cuerpo."
        }, {
            palabra: "Trietalonamida",
            parrafo: "Conocido en el mercado como TEA, utilizado en la fabricación de productos cosméticos, tales como cremas, es cristalizante en el Gel, etc. Soluble en agua, alcohol, acetona y cloroformo."
        }, {
            palabra: "Borax",
            parrafo: "Conocido también en el mercado como Borato de Sodio, su nombre científico Altincar utilizado en la fabricación de talcos y productos cosméticos también tiene usos industriales. Tener precaución con este producto sobre todo las damas. No olerlo ni saborearlo, altera las hormonas femeninas."
        }, {
            palabra: "Clorhihidróxido de Aluminio",
            parrafo: "Conocido también en el mercado como Locrán, es utilizado en la fabricación de cremas, desodorantes para las axilas. También desodorantes en barras, acrosol, etc."
        }, {
            palabra: "Aceite Mineral Blanco",
            parrafo: "Este es su nombre comercial y científico es utilizado en la fabricación de aceites para niños, cosméticos, brillantinas, ungüentos, repostería, etc."
        }, {
            palabra: "Acido Oxálico",
            parrafo: "Su nombre comercial y científico es el mismo. Es soluble en agua y alcohol es el más fuerte de los Acidos orgánicos comunes, utilizados en la fabricación del límpido, desmanchadores para pisos, etc. (Producto tóxico)."
        }, {
            palabra: "Aceite de Ricino",
            parrafo: "Conocido en el mercado como aceite de Castor, consistencia líquida amarillosa, se utiliza en la fabricación de productos cosméticos, adhesivos, plastificantes, aceites hidráulicos."
        }, {
            palabra: "Acido Nítrico",
            parrafo: "Este es su nombre comercial y científico es este mismo, usado en la fabricación de desmanchadores concentrados para pisos, nitroglicerina, nitrocelulosa, fertilizantes, etc. Evitar el contacto con la piel, utilizar gafas oscuras, guantes, mascarillas, etc."
        }, {
            palabra: "Triclosan",
            parrafo: "Su nombre científico Irgazan, utilizado en la fabricación de talco Mexana y productos antibacteriales."
        }, {
            palabra: "PP G-20",
            parrafo: "Su nombre comercial y científico es el mismo, soluble en agua, alcohol, aceites, etc. Actúa como retenedor de aroma en lociones, colonias, y también se utiliza en productos como el jabón ambientadores, etc."
        }, {
            palabra: "Talco importado de la Roca",
            parrafo: "Este es su nombre comercial y científico, producido únicamente en Italia, y la China. Se utiliza en fabricación del Talco Mexana, como materia principal. También lo utilizan en la fabricación de desodorantes en barras y otras clases de talcos."
        }, {
            palabra: "Amoníaco",
            parrafo: "Su nombre comercial y científico es el mismo. Es utilizado en la fabricación de desinfectantes para pisos. Es un producto de desinfección 100%, manejarlo con precaución, usar mascarilla, cuando se utilice."
        }, {
            palabra: "Alcohol Etílico",
            parrafo: "Conocido también en el mercado como Etanol de 96º., es materia prima en la fabricación de alcoholes cosméticos, antisépticos, es retenedor de aroma en los desinfectantes y también se utiliza en la fabricación del gel y otros."
        }, {
            palabra: "Parafinas",
            parrafo: "Este su nombre comercial y científico, su consistencia sólida y cerosa de apariencia blanca, se utiliza en la fabricación de betún, ceras para pisos, velas, etc."
        }, {
            palabra: "Cera Carnauba",
            parrafo: "Su nombre comercial es éste mismo, su apariencia amarilla y negra, consistencia sólida y cerosa. Se utiliza en la fabricación de ceras, betunes, resinas, etc."
        }, {
            palabra: "Bicromato de Potasio",
            parrafo: "Este su nombre comercial y científico, utilizado en la fabricación de límpido para darle color. También se utiliza en la fabricación de colas, tapones, adhesivos, etc."
        }, {
            palabra: "Benzoato de Sodio",
            parrafo: "Este es su nombre comercial y científico, es un preservativo en productos desinfectantes, alimenticios, cosméticos, etc."
        }, {
            palabra: "Carbonato de Calcio",
            parrafo: "Su nombre comercial y científico es el mismo. Utilizado en la fabricación de jabones en barras, cremas lavaplatos, yesos, cementos, etc."
        }, {
            palabra: "Pigmento a la Grasa",
            parrafo: "Conocido también en el mercado como Colorante a la grasa. Soluble únicamente en derivados del aceite, petróleo, pinturas, etc."
        }, {
            palabra: "Colorante Vegetal",
            parrafo: "Conocido como Colorante Tusca, para darle color a productos como shampoo, cremas, gel, etc."
        }, {
            palabra: "Polivinilpirrolidona",
            parrafo: "Conocido también en el mercado como Luviscol K-30, su consistencia en polvo, utilizado en la fabricación del Gel, barnices, colbones, pinturas, etc."
        }, {
            palabra: "Carbopol 9-40",
            parrafo: "Este es su nombre comercial y científico, se utiliza como espesante en el gel, cremas cosméticas, etc. Disolverlo en agua que gelatinice o enponje."
        }, {
            palabra: "Alcohol Cetílico",
            parrafo: "Conocido también como Lanet 16, se utiliza en la fabricación de productos cosméticos o de belleza, es un emulsionante y espesante."
        }, {
            palabra: "Monodiesterato de Glicerilo",
            parrafo: "Conocido en el mercado como Cutina, es utilizado en la fabricación de productos cosméticos, es humectante, hidratante, grasoso, etc."
        }, {
            palabra: "Acido Esteárico",
            parrafo: "Conocido en el mercado como Acido Graso, doble o triple, es utilizado en la fabricación de productos cosméticos, jabones de baño y de lavar, etc."
        }, {
            palabra: "Tween 80",
            parrafo: "Su nombre comercial y científico éste mismo, utilizado en la fabricación de lociones y productos cosméticos, es hidratante y humectante."
        }, {
            palabra: "Lanolina Hidratada",
            parrafo: "Su nombre comercial y científico es éste, utilizado en la fabricación de productos de belleza como cremas nutritivas y limpiadoras, su apariencia grasosa amarilla y sólida como la manteca."
        }, {
            palabra: "Acido Acético",
            parrafo: "Este es su nombre comercial y científico, se utiliza en las cremas, como desmanchador de la piel."
        }, {
            palabra: "Eumulgil B-2",
            parrafo: "Su nombre comercial y científico es éste. Es utilizado en la fabricación de productos cosméticos como espesante, emulsificante, desmanchante, su apariencia escarcha cerosa y grasosa."
        }, {
            palabra: "Silicato de Sodio",
            parrafo: "Conocido también en el mercado como Emulsificante, utilizado en la fabricación de jabones, detergentes líquidos, cremas lavaplatos."
        }, {
            palabra: "Extracto de Romero",
            parrafo: "Producto natural utilizado en la elaboración de shampoo, actuando como ennegrecedor del cabello y también controla la caspa."
        }, {
            palabra: "Extracto de Quina",
            parrafo: "Producto natural utilizado en la elaboración del shampoo y la crema para combatir la calvicie. Evita la caída del cabello y lo aumenta."
        }, {
            palabra: "Sulfato de Hierro",
            parrafo: "Su nombre comercial y científico es el mismo, utilizado en la fabricación de Tónico Capilar para ennegrecer el cabello y a la vez es vitamina y calcio en la raíz capilar."
        }, {
            palabra: "Tegobetaina L-7",
            parrafo: "Conocido en el mercado como Betaina, es suavizante para el cabello, obviamente anexado en el shampoo."
        }, {
            palabra: "Fosfato Trisódico",
            parrafo: "Su nombre comercial y científico es el mismo, actúa como blanqueador y desmanchador, en detergentes en polvo, también se utiliza en la purificación de azúcar, revelados fotográficos, removedores de pinturas, etc. Apariencia de granulados color blanco."
        }, {
            palabra: "Cloro en polvo",
            parrafo: "Conocido en el mercado como TII o Penclorito granulado, se utiliza en la fabricación de detergentes en polvo, tratamientos de piscinas, tratamiento de agua potable, etc."
        }, {
            palabra: "Acetato de Butilo",
            parrafo: "Su nombre comercial y científico es el mismo, es utilizado en la fabricación de removedor de esmaltes, disolventes de pinturas, adhesivos, acronales, etc."
        }, {
            palabra: "Alcanfor",
            parrafo: "Su nombre científico y comercial es el mismo, se utiliza en la elaboración de productos tales, como el Mexana, Vick Vaporub, cremas adelgazantes, etc. Mantener este producto tapado (tóxico)."
        }, {
            palabra: "Suavitex",
            parrafo: "Conocido en el mercado como suavizante textil, se utiliza en la fabricación del soflán suavizante de ropa."
        }, {
            palabra: "Alcohol Desodorizado",
            parrafo: "Es conocido también en el mercado como alcohol de perfumería, materia esencial en la fabricación de perfumes y colonias."
        }, {
            palabra: "Alcohol Isopropílico",
            parrafo: "Su nombre comercial y científico es el mismo, se utiliza en la fabricación de Limpia vidrios, y también en productos farmacéuticos."
        }, {
            palabra: "Fijador de Fragancia",
            parrafo: "Conocido también como tintura Benjuí, actúa como concentrante en lociones, colonias y desinfectantes para pisos."
        }, {
            palabra: "Varsol",
            parrafo: "Su nombre comercial y científico es el mismo, es un derivado del petróleo inflamable, consistencia líquida, se utiliza en la fabricación de ceras, betunes, etc."
        }, {
            palabra: "Arlasel",
            parrafo: "Su nombre comercial y científico es el mismo, es un agente Emulsificante y espesante en la elaboración de cremas y de uso cosmético."
        },
        {
            palabra: "Bioacumulación",
            parrafo: "Literalmente, significa la acumulación en un sistema biológico. Se refiere al almacenamiento de substancias químicas por un organismo vivo."
        },
        {
            palabra: "Bioconcentración",
            parrafo: "Proceso de acumulación de substancias químicas a concentraciones superiores a las detectadas en el medio ambiente."
        },
        {
            palabra: "Biomagnificación",
            parrafo: "Considera la acumulación de un químico a través de la cadena alimenticia."
        },
        {
            palabra: "Biomonitoreo",
            parrafo: "El uso de organismos vivos para probar la cantidad de efluentes descargados a las aguas y para probar la calidad de ellas aguas abajo del punto de descarga."
        },
        {
            palabra: "Carcinógeno",
            parrafo: "Sustancia que puede causar cáncer si un organismo es expuesto a ella por un periodo largo de tiempo."
        },
        {
            palabra: "Ciclo de vida de las sustancias químicas",
            parrafo: "producción/importación – distribución – almacenamiento – transporte – uso y disposición final."
        },
        {
            palabra: "Comercio",
            parrafo: "Actividad relacionada a la compra, venta o intercambio de bienes o servicios."
        },
        {
            palabra: "Compuestos químicos orgánicos",
            parrafo: "Sustancias animales o producidas por plantas o el hombre que contienen principalmente carbono, hidrógeno y oxígeno."
        },
        {
            palabra: "Contaminantes Orgánicos Persistentes (COPs)",
            parrafo: "Químicos orgánicos que permanecen en el medio ambiente por un periodo largo de tiempo y don dañinos a los organismos vivientes."
        },
        {
            palabra: "Convención de Estocolmo",
            parrafo: "Tratado global para proteger la salud humana y el medio ambiente de los contaminantes orgánicos persistentes (COPs)."
        },
        {
            palabra: "Dosis Letal",
            parrafo: "Cantidad de un compuesto químico presente en un organismo, que es suficiente para causar la muerte."
        },
        {
            palabra: "Efecto Saltamontes",
            parrafo: "Se refiere al transporte a larga distancia de una sustancia, de un lugar a otro lugar lejano de la tierra."
        },
        {
            palabra: "Emisiones No Intencionales",
            parrafo: "Sustancias producidas por defecto (ver productos nointencionales) de procesos de combustión industrial."
        },
        {
            palabra: "Evaluación de impacto",
            parrafo: "Un estudio de los efectos en la biota de la producción, almacenamiento, uso, manejo, transporte y disposición final de las sustancias tóxicas."
        },
        {
            palabra: "Evaluación de Riesgo",
            parrafo: " Estudio de la probabilidad y magnitud del daño a la salud humana o al medio ambiente, asociado con un agente físico o químico, o una actividad."
        },
        {
            palabra: "Fertilizante",
            parrafo: "Compuesto administrado a las plantas para promover el crecimiento. Generalmente es aplicado al suelo, para ser absorbido por las raíces, o es aplicado a las hojas, para ser absorbido por vía foliar."
        },
        {
            palabra: "Formulación",
            parrafo: "Formulación es el acto y la consecuencia de formular. Este verbo (formular), en tanto, alude a expresar una proposición, una denuncia u otro mensaje en términos precisos."
        },
        {
            palabra: "Fungicida",
            parrafo: "Sustancia usada para controlar plagas, hongos en este caso."
        },
        {
            palabra: "Herbicida",
            parrafo: "Sustancia usada para controlar plagas, arbustos, “hierbas malas”, trepadoras y otras."
        },
        {
            palabra: "Insecticida",
            parrafo: "Sustancia usada para controlar plagas, insectos en este caso."
        },
        {
            palabra: "Metales Pesados",
            parrafo: "Elementos metálicos con número atómico mayor a 20, por ejemplo, mercurio, plomo. Pueden dañar a organismos vivos en bajas concentraciones y tienden a acumularse en la cadena alimenticia."
        },
        {
            palabra: "Licencia",
            parrafo: "Autorización necesaria para trabajar con un determinado producto controlado en una region en particular, normalmente esta licencia es emitida por la autoridad competente."
        },
        {
            palabra: "Persistencia",
            parrafo: "Capacidad de una sustancia de persistir en el medio ambiente o en un organismo por un periodo largo de tiempo, porque es resistente a la degradación química y biológica. El criterio usado por la Convención de Estocolmo es que una sustancia persistente tiene una vida media en agua de 2 meses y una vida media en el suelo de 6 meses o más."
        },
        {
            palabra: "Plaguicida",
            parrafo: "Cualquier sustancia que por sí misma, o en combinación con otras, puede o es usada para eliminar plagas de los cultivos."
        },
        {
            palabra: "Plan Nacional de Implementación (PNI)",
            parrafo: "Documento preparado por cada país que ha ratificado la Convención de Estocolmo, para tomar acción contra las STP."
        },
        {
            palabra: "Prevención de la Polución",
            parrafo: "Evadir la emisión de uno o más agentes químicos o físicos al aire, agua o suelo en una cantidad, frecuencia y/o lugar que amenaza la salud humana, la vida silvestre, las plantas o cualquier otra parte del medio ambiente."
        },
        {
            palabra: "Productos no-intencionales",
            parrafo: "Sustancias emitidas durante la producción de productos finales, generalmente durante la combustión en procesos industriales. (ver emisiones no-intencionales)."
        },
        {
            palabra: "Producto Químico agrícola",
            parrafo: "Sustancia aplicada al suelo o a las plantas de un cultivo determinado."
        },
        {
            palabra: "Producto Químico de consumo",
            parrafo: "Sustancia usada sobre todo como desinfectante antiséptico, preservativo o detergente."
        },
        {
            palabra: "Producto Químico Industrial ",
            parrafo: "Compuesto que es insumo o producto de un proceso de transformación u operación industrial."
        },
        {
            palabra: "Reducción del Riesgo",
            parrafo: " Estrategia que incluye la introducción de medidas de control para reducir la probabilidad y/o magnitud de eventos que son dañinos a la salud humana y al medio ambiente."
        },
        {
            palabra: "Retardante de Llama",
            parrafo: " Material que inhibe el crecimiento del fuego. Algunas sustancias encontradas en la naturaleza, como el asbesto, así como materiales sintéticos, generalmente halocarbones como el PBDE, PCB y derivados del ácido cloréndico, son usados como retardantes de llama."
        },
        {
            palabra: "Rodenticidas",
            parrafo: "Sustancia usada para controlar plagas, roedores en este caso."
        },
        {
            palabra: "Sustancias Tóxicas Persistentes (STP)",
            parrafo: "Químicos orgánicos e inorgánicos que permanecen en el medio ambiente por un periodo largo de tiempo y don dañinos a los organismos vivientes."
        },
        {
            palabra: "Toxicidad",
            parrafo: "Capacidad de una sustancia de tener efectos dañinos para los organismos vivos y el medio ambiente. La toxicidad es evaluada por la magnitud y tipos de efectos, por las dosis necesarias para producir dichos efectos, y por las vias y tiempo de exposición a la sustancia."
        },
        {
            palabra: "Transporte a Larga Distancia",
            parrafo: "Capacidad de una sustancia de ser transportada via agua, aire o especies migratorias a un lugar remoto de donde ha sido generada."
        },
        {
            palabra: "Vida media",
            parrafo: "Es el periodo de vida promedio de una sustancia en el medio ambiente o en determinadas condiciones. Cuando una sustancia alcanza su vida media, su concentración inicial se reduce a la mitad."
        }, {
            palabra: "Acido Benzoico",
            parrafo: "Su nombre comercial y científico es el mismo ingrediente activo en la fabricación de la crema para alisar el cabello."
        }];

// Ordena de manera alfabetica

glosario.sort(function(a, b) {
    if (a.palabra > b.palabra) {
        return 1;
    }
    if (a.palabra < b.palabra) {
        return -1;
    }
    // a must be equal to b
    return 0;
});

// Genera los link y lista de glosario
definiciones = "";
definiciones += '<div class="panel panel-info"><div class="panel-heading"><h3 class=\"panel-title\"><b>Glosario de Terminos</b></h3></div><div class="panel-body">';
definiciones += imprimirxs;
definiciones += "<dl>";
for (var i = 0; i < glosario.length; i++) {
    var palabra = glosario[i];
    var parrafo = glosario[i];
    var listaPalabra = palabra.palabra;
    var listaParrafo = parrafo.parrafo;
    var listaA = listaPalabra.toUpperCase();
    var listaB = listaParrafo;
    // Genera el contenido que va a html contenido
    definiciones += "<dt>" + listaA + "</dt><dd>" + listaB + "</dd>";

}
definiciones += "</dl></div></div>";
document.getElementById('contenido').innerHTML = definiciones;
}


function extra() {

    var di = "extra/media/";
    var dib = "extra/"

    function is_img(file) {
        act = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Contiene Links' class = 'glyphicon glyphicon-file glyphicon-refresh' aria-hidden = 'true'> </span>";
        acte = " <span data-toggle = 'tooltip' data-placement = 'bottom' title = 'Contiene Links' class = 'glyphicon glyphicon-file glyphicon-warning-sign' aria-hidden = 'true'> </span>";

        var ifrmad = "";
        ifrmad += "<p class='text-right'>" +
            "<button autocomplete='off' id='myButton' class='btn btn-danger btn-xs' " +
            "title = 'Recargar para reconocer Extensiones Instaladas' onclick = 'recarga();' href='#Extensiones'>" +
            "Recargar Sistema " + act + "</button>";
        ifrmad += "</p>";
        ifrmad += "<div class='alert alert-danger' role='alert'> <strong>" + acte + " Lo Sentimos!</strong> Usted no posee instalado Extensiones de este Curso <a href='http://bit.ly/extensionespdl' target='_blank' class='btn btn-default'><b>CONSULTAR EXTENSIONES DISPONIBLES AQUI</b> </a> </div>";


        ifrmad += "<div class=\"panel panel-info\">";
        ifrmad += "<div class=\"panel-heading\"><b>Como Instalar Extensiones</b><\/div>";
        ifrmad += "<div class=\"panel-body\">";
        ifrmad += "<h1>Como Instalar una Extension<\/h1>";
        ifrmad += "<p>El proceso de instalacion es simple, y se explica y detalla paso a paso en estos 5 sencillos pasos:<\/p>";
        ifrmad += "<ol><li>Es sencillo, el proceso consiste en adquirir el paquete correspondiente a la extension, este archivo lo recibira en formato .7z que es el formato de compresion standar con el que trabajamos en J.E Suministros y Mas, C.A.<\/li>";
        ifrmad += "<p class=\"text-center\">";
        ifrmad += "<img class=\"img-thumbnail\" src=\"media\/imagen\/portadas\/extra\/comprimido.png\" alt=\"Archivo Comprimido\">";
        ifrmad += "<\/p>";
        ifrmad += "<li>Debe descomprimir el archivo de nombre <strong>extra<\/strong> descargado.<\/li>";
        ifrmad += "<p class=\"text-center\">";
        ifrmad += "<img class=\"img-thumbnail\" src=\"media\/imagen\/portadas\/extra\/extraer.png\" alt=\"Archivo Comprimido\">";
        ifrmad += "<\/p>";
        ifrmad += "<li>Usted obtendra una carpeta de nombre <strong>extra<\/strong> <\/li>";
        ifrmad += "<p class=\"text-center\">";
        ifrmad += "<img class=\"img-thumbnail\" src=\"media\/imagen\/portadas\/extra\/carpetaextraida.png\" alt=\"Archivo Comprimido\">";
        ifrmad += "<\/p>";
        ifrmad += "<li>Todos los archivos contenidos en la carpeta deben seleccionarlo y copiarlo<\/li>";
        ifrmad += "<p class=\"text-center\">";
        ifrmad += "<img class=\"img-thumbnail\" src=\"media\/imagen\/portadas\/extra\/copiarextra.png\" alt=\"Archivo Comprimido\">";
        ifrmad += "<\/p>";
        ifrmad += "<li>Tome en cuenta que su curso posee 3 carpetas que son: <strong>extra</strong>, <strong>media</strong>  y <strong>pag</strong>, y un archivo <strong>index.html</strong> que es el que le permite acceder a su curso. Los archivos copiados en el paso anterior deben ser pegados dentro de la carpeta <strong>extra<\/strong> de su curso, ver imagen de referencia<\/li>";
        ifrmad += "<p class=\"text-center\">";
        ifrmad += "<img class=\"img-thumbnail\" src=\"media\/imagen\/portadas\/extra\/carpetaextra.png\" alt=\"Archivo Comprimido\">";
        ifrmad += "<\/p>";
        ifrmad += "<\/div>";
        ifrmad += "<\/div>";

        var img = new Image();
        img.src = file;
        img.onload = function () {
            extension();
        }
        img.onerror = function () {
            document.getElementById("contenido").innerHTML = ifrmad;
        }
    }
    is_img(di + "img.jpg");

}

function recarga() {

    var x = "";
    var x = document.getElementsByTagName("BODY")[0];
    x.style.backgroundColor = "red";
    document.location.reload();
    // return extra();
    //var x += document.location.reload();

}



function formulas() {

    const botonMateriales = document.createElement("button");
botonMateriales.setAttribute("type", "button");
botonMateriales.setAttribute("class", "btn btn-info btn-xs col-xs-12 form-inline");
botonMateriales.setAttribute("data-toggle", "modal");
botonMateriales.setAttribute("data-target", "#myModal");
botonMateriales.innerHTML = "Herramientas Generales";

const botonRecomendaciones = document.createElement("button");
botonRecomendaciones.setAttribute("type", "button");
botonRecomendaciones.setAttribute("class", "btn btn-info btn-xs col-xs-12 form-inline");
botonRecomendaciones.setAttribute("data-toggle", "modal");
botonRecomendaciones.setAttribute("data-target", "#myModal2");
botonRecomendaciones.innerHTML = "Recomendaciones Generales";

const botonera = document.createElement("div");
botonera.setAttribute("class", "btn-group");

botonera.appendChild(botonMateriales);
botonera.appendChild(botonRecomendaciones);

    var materiales = "<p>Son Bastante sencillos y casi que de uso comun, hay cosas que pueden ser sustituidos por otros instrumentos que hagan la misma funcion, como por ejemplo los instrumentos aforados pueden ser sustituidos por jarras o vasos de uso domestico que tengan marcas de medidas.</p>" + olli + "Tobos plasticos con 4 veces la capacidad de producto a preparar." + lili + "Paletas de madera, palos de escoba, cabos de madera." + lili + "PH metro o bandas medidoras de PH (las bandas pueden resultar mas economicas)." + lili + "Probetas de varias capacidades, tambien le puede servir jarras con medidas marcadas." + lili + "Delantal de cuero de chivo o plastico resistente." + lili + "Guantes aislantes de cuero y de goma. Evite manipular acidos con guantes de latex." + lili + "Lentes de seguridad, careta." + lili + "Tapa boca, filtros, deshumificadores, etc." + lili + "Pesos digitales o analogicos." + lili + "Embudos de varios tamaños." + lili + "Vestimenta adecuada, bragas, batas de laboratorio." + lili + "Se recomienda tener una fuente de agua." + liol;

    var recomendaciones = "<p>En Terminos Generales:</p>" + olli + "En los casos donde no se especifique una concentracion en particular de algun componente, usted debe asumir que los niveles a utilizar son el mas puro que encuentre en el mercado, tome en cuenta que hay productos que son comercializados en varios niveles de concentracion." + lili + "Si hay productos que son liquidos pero la unidad de medida utilizada en el aplicativo es la de: gramos - kilos, usted debera utilizar dicha medida, pesando el liquido en cuestion." +lili + "En productos que tendran contacto con la piel usted debe tomar en cuenta que los niveles de PH deben ser Neutros es decir que tiendan a 7, para comprender como lograr estabilizar algun producto favor visitar la Leccion sobre <a data-dismiss='modal' onclick='ph();' name='NFPA' title='Ver PH' href='#ph'> <b>PH</b></a>." + lili + "Las instalaciones a nivel Industrial deben cumplir con ciertas normas, para ampliar detalles puede visitar la Leccion sobre <a data-dismiss='modal' onclick='industrial();' name='Instalaciones Industriales' title='Instalaciones Industriales' href='#industriales'> <b>Instalaciones Industriales</b></a>." + lili + "Si desea ampliar informacion sobre el <a data-dismiss='modal' onclick='nfpa();' name='NFPA' title='Rombo de Seguridad NFPA' href='#NFPA'> <b>Rombo de Seguridad NFPA</b></a>." + lili + "Mantener el area de trabajo despejada." + lili + "Evitar el uso de herramientas no adecuadas." + liol;


    var resultado = "";
    resultado += "<div class=\"container-fluid\">";
    resultado += "<div class=\"row\">";
    resultado += "<div class=\"col-xs-12 col-sm-6\">";
    //resultado += "<div id=\"lista\"><\/div>";
    resultado += "<\/div>";
    resultado += "<div class=\"col-xs-12 col-sm-6 text-right \">" + imprimirxs + "<\/div>";
    resultado += "<div class=\"clearfix\"><\/div>";
    modulosinstalados = secciones.length - 1;
    if (modulosinstalados == 1) {
        mensajemodulos = "Usted posee " + modulosinstalados + " Modulo Extra Instalado y un total de ";
    } else if (modulosinstalados == 0){
        modulosinstalados = "";
        mensajemodulos = "Usted No posee Modulos Instalados y un total de ";
    } else
    {
        mensajemodulos = "Usted posee " + modulosinstalados + " Modulos Extras Instalados y un total de ";
    }
    resultado += "<div class='small alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><span class='glyphicon glyphicon-ok-sign' aria-hidden='true'> </span> <strong>" + mensajemodulos + producto.length + " Formulas de Productos Instaladas.</strong> </div>";
    resultado += "<\/div>";
//    resultado += "<div class=\"clearfix\"><\/div>";
//    resultado += "<div class= \"col-xs-12 col-sm-3 \">";
//    resultado += botonrecomendaciones;
//    resultado += "<\/div>";
//
//
//    resultado += "<div class= \"col-xs-12 col-sm-3 \">";
//    resultado += botonmateriales;
//    resultado += "<\/div>";
//    resultado += "<div class='clearfix'></div>";
resultado += document.body.appendChild(botonera);

    resultado += "<div class=\"panel panel-info\">";
    resultado += "<div class=\"panel-heading\">";
    resultado += "<div class='col-xs-12 '>Seleccione el Modulo";
    resultado += "<div class='col-xs-12 ' id=\"primerselect\"><\/div><\/div>";
    //resultado += "<div class='col-xs-12'>Seleccione el Producto";
    resultado += "<div class='col-xs-12' id=\"lista\"><\/div>";
    resultado += "<h1 class=\"col-xs-12 text-uppercase panel-title\" id='formula' name=\"titulo\"><\/h1>";

  //  resultado += "<div class=\"col-xs-12\">";

    resultado += "<form class=\"form-inline\">";
    resultado += "<div class=\"form-group\">";
    resultado += "<br>";
    resultado += "<label id=\"tipo\" for=\"exampleInputName2\"><\/label><br>";
    //18062020
    //resultado += "<input class=\"form-control\" placeholder=\"Cantidad de Producto\" id=\"entrada\" onkeyup=\"validarSiNumero(this.value);\" \/>";
resultado += "<div class='col-xs-12 ' id=\"inputentrada\"><\/div><\/div>";


    resultado += "<\/div>";
    resultado += "<\/form>";
    //resultado += "<\/div>";

    resultado += "<!-- Modal -->";
    resultado += "<div class=\"modal fade bs-example-modal-lg\" id=\"myModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\">";
    resultado += "<div class=\"modal-dialog\" role=\"document\">";
    resultado += "<div class=\"modal-content\">";
    resultado += "<div class=\"modal-header\">";
    resultado += "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;<\/span><\/button>";
    resultado += "<h4 class=\"modal-title\" id=\"myModalLabel\"> Herramientas de uso general<\/h4>";
    resultado += "<\/div>";
    resultado += "<div id=\"materiales\" class=\"modal-body\">";
    resultado += materiales;
    resultado += "<\/div>";
    resultado += "<div class=\"modal-footer\">";
    resultado += "<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cerrar<\/button>";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";



    resultado += "<!-- Modal 2 -->";
    resultado += "<div class=\"modal fade bs-example-modal-lg\" id=\"myModal2\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\">";
    resultado += "<div class=\"modal-dialog\" role=\"document\">";
    resultado += "<div class=\"modal-content\">";
    resultado += "<div class=\"modal-header\">";
    resultado += "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;<\/span><\/button>";
    resultado += "<h4 class=\"modal-title\" id=\"myModalLabel\"> Recomendaciones Generales<\/h4>";
    resultado += "<\/div>";
    resultado += "<div id=\"recomendaciones\" class=\"modal-body\">";
    resultado += recomendaciones;
    resultado += "<\/div>";
    resultado += "<div class=\"modal-footer\">";
    resultado += "<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cerrar<\/button>";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";


    resultado += "<div class=\"panel-body\">";
    //resultado += "<div class=\"clase1\" id='tituloproducto'><\/div>";
    resultado += "<div class=\"clase1\" id='nfpa'><\/div>";
    resultado += "<div class=\"clase1\" id='salida'><\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";
    resultado += "<div class=\"well\" id='procedimiento'><\/div>";
    resultado += "<\/div>";



    //document.getElementById('materiales').innerHTML = materiales;
    document.getElementById('contenido').innerHTML = resultado;
// esta seccion muestra el reloj de espera
    //document.getElementById('tipo').innerHTML = "A la Espera de su Seleccion" + '<img width="5%" src="media/imagen/reloj.gif"></img>';


}

function filtrarseccion() {
    document.getElementById('formula').innerHTML = '';
    document.getElementById('tipo').innerHTML = 'A la Espera de su Selección';
    var seccion = document.getElementById('selectSecciones').value;
    salidaproducto(seccion);
}

function primerselect() {

    var select = document.createElement('select');
    select.id = 'selectSecciones';
    select.classList.add('form-control');
    select.addEventListener('change', filtrarseccion);
    var option = document.createElement('option');
    option.disabled = true;
    option.selected = true;
    option.textContent = 'Filtrar por Modulo';
    select.appendChild(option);
    secciones.forEach(function(seccion) {
        option = document.createElement('option');
        option.textContent = seccion;
        select.appendChild(option);

    });

    document.getElementById('primerselect').appendChild(select);

}

function salidaproducto(seccion) {
    document.getElementById('lista').innerHTML = '';
    var select = document.createElement('select');
    select.id = 'mySelect';
    select.classList.add('form-control');
    select.addEventListener('change', selector);
    var option = document.createElement('option');
    option.disabled = true;
    option.selected = true;
    option.textContent = sup;
    select.appendChild(option);
    var productofiltrado = producto;

    if (seccion) {
        productofiltrado = producto.filter(function (item) {
            return item.grupo === seccion;
        });
    }

    productofiltrado.forEach(function (item) {
        option = document.createElement('option');
        option.textContent = item.n;
        select.appendChild(option);
    });

    // DESPUES DE Seleccione el Modulo
    document.getElementById('lista').innerHTML='Ahora Seleccione el Producto:';

    document.getElementById('lista').appendChild(select);

    //document.getElementById('lista').innerHTML = "A la Espera de su Seleccion" + '<img width="5%" src="media/imagen/reloj.gif"></img>';
    document.getElementById('tipo').innerHTML = "A la Espera de su Seleccion" + '<img width="5%" src="media/imagen/reloj.gif"></img>';

    document.getElementById('inputentrada').innerHTML = '<input class=\"form-control\" placeholder=\"Cantidad de Producto\" id=\"entrada\" onkeyup=\"validarSiNumero(this.value);\" \/>';




}

/*
function salidaproducto() {
    var select = document.createElement('select');
    select.id = 'mySelect';
    select.classList.add('form-control');
    select.addEventListener('change', selector);
    var option = document.createElement('option');
    option.disabled = true;
    option.selected = true;
    option.textContent = sup;
    select.appendChild(option);
    for (li in producto) {
        option = document.createElement('option');
        option.textContent = producto[li].n;
        select.appendChild(option);

    }

    document.getElementById('lista').appendChild(select);

}

*/

function selector(UI) {

    fo = document.getElementById('mySelect').value;
    //document.getElementById('formula').innerHTML = "<br><b>"+fo+"</b><br>";
    document.getElementById('formula').innerHTML = "<br><b>"+fo+"</b><br>";
    document.getElementById('entrada').value = "";
    document.getElementById('salida').innerHTML = "";
    document.getElementById('procedimiento').innerHTML = "";
    productoSeleccionado = producto.find(x => x.n === this.value);
    tipoEl = document.getElementById('tipo');
    if (productoSeleccionado) {
        tipoEl.innerText = (productoSeleccionado.t === 'liq' ?
            'Indique la cantidad de LITROS de' + ' ' + fo.toUpperCase() + ' que desea preparar: ' : 'Indique la cantidad de KILOS de' + ' ' + fo.toUpperCase() + ' que desea preparar: ');
    } else {
        tipoEl.innerText = '';
    }


    var az ='';
    var ro ='';
    var am ='';
    var bl ='';

    az = productoSeleccionado.az;
    ro = productoSeleccionado.ro;
    am = productoSeleccionado.am;
    bl = productoSeleccionado.bl;
    bla = productoSeleccionado.bla;

    var azul = "";
    var rojo = "";
    var amarillo = "";
    var blanco = "";

var valores = [0,1,2,3,4];
    var riesgos = [
    "Sin riesgo",
    "Poco peligroso",
    "Peligroso",
    "Muy peligroso",
    "Mortal"];
    var inflamabilidad = [
    "No se Inflama",
    "Inflama a mas de 93°C",
    "Inflama a partir de 93°C",
    "Inflama a parir de 37°C ",
    "Inflama a parir de 25°C "];
    var reactividad = [
    "Estable",
    "Inestable en Caso de Calentamiento",
    "Inestable en caso de cambio quimico violento",
    "Puede Explotar en caso de choque o calentamiento",
    "Puede explotar subitamente"];
    var riesgoesp = [
        "Oxidante",
        "Corrosivo",
        "Radioactivo",
        "No Usar Agua",
        "Riesgo Biologico",
        "Ninguno"];

valores.forEach(function(elemento) {
    if(az == elemento)
    azul = "Nivel de Riesgo: ["+elemento+"] " + riesgos[elemento];
    if (ro == elemento)
    rojo = "Inflamabilidad: ["+elemento+"] " + inflamabilidad[elemento];
    if (am == elemento)
    amarillo = "Reactividad: ["+elemento+"] " + reactividad[elemento];
    if (bla == elemento)
    blanco = "Riesgo Especial: " + riesgoesp[elemento];
});

    switch (bla) {
        case 0:
            // Oxidante
            var bl = "OXI";
            break;
        case 1:
            // Corrosivo
            var bl = "COR";
            break;
        case 2:
            // Radioactivo
            var bl = "<img alt= '' src='media/imagen/general/24px_simbolo_riesgo_radiactivo.png'> ";
            break;
        case 3:
            // No Usar Agua
            var bl = "<s>W</s>";
            break;
        case 4:
            // Riesgo Biologico
            var bl = "<img alt= '' src='media/imagen/general/24px_simbolo_riesgo_biologico.png'> ";
            break;
             case 5:
            // Riesgo Biologico
            var bl = "";
            break;
    }


    var rombo = "";

    rombo += "<tbody>";

    rombo += "<tr>";
    rombo += "<th><b>" + fo.toUpperCase() + "</b><br><a data-toggle='modal' data-target='#myModal3' name='NFPA' title='Cargar Explicacion sobre Rombo de Seguridad NFPA' href='#NFPA'><b>Rombo NFPA<b/> </a><\/th>";
    rombo += "<\/tr>";
    rombo += "<tr>";
    rombo += "<td align=\"left\">";
    rombo += "<div class = \"rombo1\" >";
    rombo += "<div class = \"rombo2\" >";
    rombo += "<p>";
    rombo += "<img alt=\"NFPA 704.svg\" src=\"media\/imagen\/general\/NFPA_704.svg.png\"";
    rombo += "width=\"75\" height=\"75\" srcset=\"media\/imagen\/general\/NFPA_704.svg.png 1.5x, media\/imagen\/general\/NFPA_704.svg.png 2x\"";
    rombo += "data-file-width=\"600\" data-file-height=\"600\">";
    rombo += "<\/p>";
    rombo += "<\/div>";
    rombo += "<div class = \"rojo\" title=\"" + rojo + "\" \">"+ro+"<\/div>";
    rombo += "<div class = \"azul\" title=\"" + azul + "\">" + az +"<\/div>";
    rombo += "<div class = \"amarillo\" title=\"" + amarillo + "\" >" + am +"<\/div>";
    rombo += "<div class = \"blanco\" title=\"" + blanco + "\" >";
    rombo += "" + bl;
    rombo += "<\/div>";
    rombo += "<\/div>";
    rombo += "<\/td>";
    rombo += "<\/tr>";
    rombo += "<\/tbody>";


    rombo += "<!-- Modal 3 -->";
    rombo += "<div class=\"modal fade\" id=\"myModal3\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\">";
    rombo += "<div class=\"modal-dialog\" role=\"document\">";
    rombo += "<div class=\"modal-content\">";
    rombo += "<div class=\"modal-header\">";
    rombo += "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;<\/span><\/button>";
    rombo += "<h4 class=\"modal-title\" id=\"myModalLabel\">ROMBO NFPA<\/h4>";
    rombo += "<\/div>";
    rombo += "<div class=\"modal-body text-center\">";
    rombo += " <img width=\"60%\" src=\"media\/imagen\/general\/rombo_nfpa_704.png\" alt=\"\">";
    //rombo += "<div id='rombo_nfpa'></div>";
    rombo += "<p>Si desea acceder a la seccion donde se amplia informacion sobre el Rombo NFPA puede hacerlo accediendo a: <a data-dismiss='modal' onclick='nfpa();' name='NFPA' title='Rombo de Seguridad NFPA' href='#NFPA'> <b>Rombo de Seguridad NFPA</b></a></p>";
    rombo += "<\/div>";
    rombo += "<div class=\"modal-footer\">";
    rombo += "<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Cerrar<\/button>";
    rombo += "<\/div>";
    rombo += "<\/div>";
    rombo += "<\/div>";
    rombo += "<\/div>";


    document.getElementById('nfpa').innerHTML = rombo;
    document.getElementById("mySelect").value = sup;
    tratamiento_producto(UI);
}
//salidaproducto();

function tratamiento_producto(UI){

lsin = " Litro de ";
lplu = " Litros de ";
ksin = " Kilo de ";
kplu = " Kilos de ";
    tipoproducto = "";
    tipoproducto = productoSeleccionado.t;
    if (tipoproducto == "liq"){
    if (UI == 1){tipoproducto = lsin;} else {tipoproducto = lplu;}
//   tipoproducto = " Litros de ";
    }
    else {
    if (UI == 1){tipoproducto = ksin;} else {tipoproducto = kplu;}
//        tipoproducto = " Kilos de ";
    }
    return tipoproducto;
}

//DEFINIENDO ENTRADA DE VARIABLES AL SISTEMA


function entrada() {
    if (UI = document.getElementById('entrada')) { eval(document.getElementById('formula').textContent.toLowerCase().replace(/ /gi, '_') + '()'); }
}

//VALIDAR SI VALOR INGRESADO ES VALIDO
function validarSiNumero(UI) {


    if (!/^([0.1-9])*$/.test(UI)) {
        var nada = "<div class='alert alert-info' role='alert'> <span class='glyphicon glyphicon-alert' aria-hidden='true'></span> </span> <span class='sr-only'>Error:</span> <strong> ALGO HA OCURRIDO..! </strong> El Valor: <b>" + UI + "</b> no es un numero valido, intente nuevamente utilizando numeros desde <strong> 0.1 </strong> hasta la cantidad de " + tipoproducto + " <strong>" + fo.toUpperCase() + " </strong> que usted desee fabricar.</div>";
        document.getElementById('salida').innerHTML =
            nada;
        document.getElementById("procedimiento").innerHTML = "";
    } else if (UI === "") {
        var nada = "<div class='alert alert-warning' role='alert'> <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> <span class='sr-only'>Error:</span><strong> LO SENTIMOS..! </strong> Pero usted no ha escrito nada, intente nuevamente utilizando numeros desde <strong> 0.1 </strong> hasta la cantidad de " + tipoproducto + " <strong>" + fo.toUpperCase() + "</strong> que usted desee fabricar.</div>";
        document.getElementById('salida').innerHTML =
            nada;
        document.getElementById("procedimiento").innerHTML = "";
    } else if (UI == 0) {
        var nada = "<div class='alert alert-danger' role='alert'> <span class='glyphicon glyphicon-warning-sign' aria-hidden='true'></span> </span> <span class='sr-only'>Error:</span> <strong> LO SENTIMOS..! </strong> Pero Cero (0) no es un numero valido, intente nuevamente utilizando numeros desde <strong> 0.1 </strong> hasta la cantidad de " + tipoproducto + " <strong>" + fo.toUpperCase() + "</strong> que usted desee fabricar.</div>";

        document.getElementById('salida').innerHTML =  nada;
        document.getElementById("procedimiento").innerHTML = "";
    } else if (UI < 0.1) {
        var nada = "<div class='alert alert-danger' role='alert'> <span class='glyphicon glyphicon-warning-sign' aria-hidden='true'></span> </span> <span class='sr-only'>Error:</span> <strong> LO SENTIMOS..! </strong> El numero que ingreso es muy pequeño, intente nuevamente utilizando numeros desde <strong> 0.1 </strong> hasta la cantidad de " + tipoproducto + " <strong>" + fo.toUpperCase() + "</strong> que usted desee fabricar.</div>";

        document.getElementById('salida').innerHTML = nada;
        document.getElementById("procedimiento").innerHTML = "";
    }
    else if (UI === "010101100100010101001110010001010101101001010101010001010100110001000001") {
        var nada = "<div class='alert alert-warning' role='alert'></div><div class='alert alert-info text-center' role='alert'><span class='glyphicon glyphicon-star' aria-hidden='true'></span> <span class='glyphicon glyphicon-star' aria-hidden='true'></span> <span class='glyphicon glyphicon-star' aria-hidden='true'></span> <span class='glyphicon glyphicon-star' aria-hidden='true'></span> <span class='glyphicon glyphicon-star' aria-hidden='true'></span> <span class='glyphicon glyphicon-star' aria-hidden='true'></span> <span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='alert alert-danger' role='alert'></div>";

        document.getElementById('salida').innerHTML = nada;
        document.getElementById("procedimiento").innerHTML = "";
    }
    else {
        entrada();
    }

}




// CICLO
function tratamiento(UI) {
    for (xy in componente) {
            tt = "";
            uu = "";
        if (componente['xy'].t == "liq") {
          if (componente['xy'].v == 1) {
                uu = " Litro ";
                tt = " Mililitro ";
          } else {
                uu = " Litros ";
                tt = " Mililitros ";
          }
 }
        else {
          if (componente['xy'].v == 1) {
                uu = " Kilo ";
                tt = " Gramo ";
          } else {
                uu = " Kilos ";
                tt = " Gramos ";
          }
 }
        if (UI <= 1) { componente['xy'].v = (componente['xy'].v * 1000).toFixed(2) + tt;}
        else {
             if (componente['xy'].v * 1000 >= 1000) { componente['xy'].v = (componente['xy'].v).toFixed(2) + uu; }
            else {
                componente['xy'].v = (componente['xy'].v * 1000).toFixed(2) + tt;
            }
        }
    }
    return componente['xy'].v;
}



function seleccion() {
    var selec = document.getElementById("mySelect").value;
    document.getElementById("formula").innerHTML = selec;
}


function popove(){
        $('[data-toggle="popover"]').popover({
           placement: 'auto right',
           trigger: 'hover',
           html: true
        });
}



//DEFINIENDO FUNCIONES
//PRUEBA
function prueba_entrada() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(91.8);
    componente['01'].v = p(3);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['01'].n + componente['01'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> PRODUCTO DE PRUEBA";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function jabon_en_pasta() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['56'].v = p(15.8);
    componente['00'].v = p(9.8);
    componente['57'].v = p(45.33);
    componente['58'].v = p(11.86);
    componente['59'].v = p(25);
    componente['07'].v = p(1.46);
    componente['08'].v = p(1.46);

    tratamiento(UI);
    tratamiento_producto(UI);


    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['56'].n + componente['56'].v + lili +
        componente['00'].n + componente['00'].v + lili +
        componente['57'].n + componente['57'].v + lili +
        componente['58'].n + componente['58'].v + lili +
        componente['59'].n + componente['59'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> Mezclar la Pasta Sulfonica con el Agua y cuando estén bien mezclado agregar el Talco Neutro previamente tamizado, este proceso se recomienda hacerlo unas 3 veces para facilitar el trabajo de homogenizacion, debe continuar mezclando. Cuando este todo integrado agregar el Perborato de Sodio y el Sulfato de Sodio ya por ultimo incorporar el Color y la Fragancia. <h4>Nota:</h4><p>Si desea que el producto sea más consistente se debe aumentar la cantidad de Sulfato de Sodio, puede ser hasta un maximo de 3 veces la cantidad sugerida en esta formula. </p><p>El Perborato además de conservante es suavizante. </p><p>El Talco se debe cernir antes de mezclarlo para que sea mas fácil de mezclar y evitar cualquier elemento extraño presente.</p><br><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Siempre respetar el orden indicado, este producto se debe preparar en un lugar abierto y con suma precaución debido a los vapores y polvos que pueden estar en el ambiente al momento de hacer esta preparacion.</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}

function cera_para_pulir_muebles() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['49'].v = p(33.33);
    componente['44'].v = p(33.33);
    componente['55'].v = p(33.33);

    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['49'].n + componente['49'].v + lili +
        componente['44'].n + componente['44'].v + lili +
        componente['55'].n + componente['55'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>En un envase se pone a derretir la Cera de Abeja a fuego bajo para que no se queme, se recomienda efectuar este procedimeinto aplicando la tecnica de baño de Maria.</p><p>Ya una vez derretida se vacía en la taza medidora para poder medir la cantidad exacta y agregar la misma cantidad de Trementina y del Aceite de Transmision. Se revuelve la mezcla y se deja enfriar.</p><p>Ya fría se agrega un poco de Trementina de Pino y un poco de Aceite de Transmision y con ayuda de la espátula se integra muy bien para que la mezcla tome consistencia de crema, se envasa y se tapa, recuerde etiquetar el recipiente anotando el nombre del producto y fecha de elaboracion.</p><br><div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Procurar siempre utilizar la tecnica de Baño de Maria, de esa manera evita que su preparacion se queme..</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function cera_para_pulir_carros() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['50'].v = p(8.33);
    componente['49'].v = p(16.66);
    componente['04'].v = p(8.33);
    componente['44'].v = p(83.33);

    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['50'].n + componente['50'].v + lili +
        componente['49'].n + componente['49'].v + lili +
        componente['04'].n + componente['04'].v + lili +
        componente['44'].n + componente['44'].v + liol;


    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>Se debe triturar la cera de carnauba en el mortero o con un martillo sobre el piso dentro de una bolsa, hasta obtener un polvo fino (mientras mas fino mejor) luego se tamiza y se deposita en una olla donde tambien debe colocar la cera de abeja, esto debe derretirlo con ayuda de un baño de Maria hasta que se derritan totalmente ambas ceras. Ya una vez en estado líquido se apaga el fuego y se coloca la cacerola fuera de la estufa en un lugar seguro, para poder agregar la Trementina de Pino, este proceso debe hacerlo poco a poco, con cuidado de no hacerlo de manera brusca.</p><p> Posteriormente se coloca la cacerola nuevamente en la estufa y encendemos. Si tenemos una cacerola con tapa calentamos durante un minuto con fuego máxima. Si tenemos cacerola sin tapa calentamos durante dos minutos con fuego al mínimo.</p><p>Con ayuda de un batidor, se agita la mezcla líquida durante un tiempo que permita homogenizar la preparacion.</p><p>Se retira del baño de Maria, para dejarla enfriar en un lugar seguro expuesto al aire libre durante un minimo de 3 horas. Al termino del tiempo incorporamos la mezcla con el batidor agitando suavemente hasta obtener una consistencia semipastosa, la cual se deja reposar durante 5 horas.<br> Por último se agrega la cucharada chica de aceite mineral y se realiza un agitado rápido de treinta segundos con el batidor, de tal forma que obtengamos una cera cremosa y suave.</p><h4>Envasado y conservación:</h4><ol><li>Conserve este producto en una lata bien tapada. </li><li>No olvide colocarle una etiqueta con el nombre del producto, la fecha de elaboración y de caducidad. </li><li> Es conveniente guardar su cera para auto en un lugar seco y oscuro. </li></ol><h4>Caducidad:</h4>La cera para autos elaborada mediante esta tecnología tiene una duración aproximada de 3 meses.<h4>Modo de uso:</h4><ol><li>El auto se debe lavar y secar muy bien antes de aplicar la cera.</li><li>Aplique la cera con estopa.</li><li>Para evitar que la cera se seque al aplicarla, realice la operación por secciones.</li><li>Con un trapo suave frote rigurosamente en las secciones enceradas de tal forma que no aparezca ninguna huella de grasa.</li></ol><h4>Recomendaciones:</h4><ol><li>Durante la elaboración del producto use guantes.</li><li>Al elaborar este producto se debe efectuar en un cuarto ventilado.</li><li>En caso de que sobren ingredientes, se etiquetan sus respectivos envases y se conservan en un lugar seco y oscuro.</li></ol><br><div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Procurar siempre utilizar la tecnica de Baño de Maria, de esa manera evita que su preparacion se queme..</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function limpia_hornos() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(86.53);
    componente['53'].v = p(4.80);
    componente['36'].v = p(9.61);
    componente['07'].v = p(0.03);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['53'].n + componente['53'].v + lili +
        componente['36'].n + componente['36'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <ol><li>En un recipiente plastico que posea una capacidad del triple de la cantidad que va a preparar se debe colocar la mitad de agua, para diluir la harina sin que se formen grumos</li><li>En otro recipiente debe colocar la otra mitad del agua, aplicando la soda caustica en escamas revolviendo continuamente para que se disuelva totalmente.</li><li>Como la solución de soda estara caliente por su disolucion en el agua hay que mezclar inmediatamente estos dos pasos, para que la harina espese con el calor; y el producto se vuelva consistente de lo contrario, se calienta un poco para que espese pero lo mas apropiado es que esto suceda con el calor de la reaccion, tome en cuenta que puede que la harina espese bastante el liquido.</li><li>Para evitar que se formen grumos espesos se debe agitar fuerte y constantemente para que la mezcla sea uniforme y por consecuencia se tenga buena consistencia del producto.</li><li> Adicionar el color si lo desea, revolviendo muy bien, despues se deja en reposo hasta que enfrie y se empaca.</li></ol><h4>Recomendaciones:</h4>Este producto es un desengrasante excelente para las cocinas y hornos, aplicando directamente sobre la grasa se deja actuar hasta que esta sea removida, cuando se fabrique este producto hay que proteger la piel, las manos, los ojos; con la correspondiente seguridad, guantes industriales, gafas y tapabocas de calidad industrial, ya que la soda caustica es altamente corrosiva para los tejidos organicos. Saldra vapor y un fuerte olor que naturalmente producira picazon en el rostro y manos, por consiguiente es recomendable protegerse en este procedimiento.<br><br><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Siempre procurar protegerse la mayor parte de la piel expuesta, este producto se debe preparar en un lugar abierto y con proteccion en la mayor parte del cuerpo, <b> TENGA SUMA PRECAUCION.</b></div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function limpiador_de_pocetas() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['30'].v = p(33.75);
    componente['00'].v = p(60);
    componente['52'].v = p(5.5);
    componente['33'].v = p(0.75);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['30'].n + componente['30'].v + lili +
        componente['00'].n + componente['00'].v + lili +
        componente['52'].n + componente['52'].v + lili +
        componente['33'].n + componente['33'].v + liol;


    var procedimiento = "<h3>PROCEDIMIENTO:</h3> El procedimiento para hacer este producto es bastante sencillo si se quiere, basicamente consiste en unir los componentes pero debe cuidar hacerlo en un orden especifico,en un envase con el triple de capacidad estrictamente en el siguiente orden: <ol><li>Colocar el Agua e incorporarle el Hipoclorito.</li><li>Luego agregar el Acido Fosforico.</li><li>Y por ultimo incorporar el Amonio Cuatrenario.</li><br><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Siempre respetar el orden indicado, Este producto se debe preparar en un lugar abierto o ventilado y con suma precaución debido a los vapores que emiten los componentes aca empleados.<b>TENGA SUMA PRECAUCION</b></div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function ambientador() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['27'].v = p(93);
    componente['08'].v = p(5);
    componente['23'].v = p(2);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['27'].n + componente['27'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + lili +
        componente['23'].n + componente['23'].v + liol;


    var procedimiento = "<h3>PROCEDIMIENTO:</h3><ol><li>En un deposito poner el Alcohol.</li><li>En otro deposito mezclar el perfume o la fragancia con el Nonilfenol y agitarlo hasta obtener una buena homogenización.</li><li>Mezclar los ingredientes en un solo deposito y agitar vigorozamente.</li><li>Envasarlo y que repose por lo menos 24 horas para que la fragancia se integre perfectamente con el alcohol.</li></ol><p>En el mercado se consiguen varios tipos de fragancias, se recomiendan fragancias concentradas y no de uso cosmetologico, ya que tienden a ser mas costosos y este tipo de producto se debe utilizar para ser aplicado en ambientes como habitaciones, salas de baño, cocinas, etc.</p><br><div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> No debe aplicarse en la piel, este producto puede ocacionar irritacion de las mucosas, evite el contacto con los ojos.</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function antitranspirante_en_barra() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['47'].v = p(22.22);
    componente['48'].v = p(33.33);
    componente['49'].v = p(66.66);
    componente['51'].v = p(88.88);
    componente['08'].v = p(11.11);

    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['47'].n + componente['47'].v + lili +
        componente['48'].n + componente['48'].v + lili +
        componente['49'].n + componente['49'].v + lili +
        componente['51'].n + componente['51'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;


    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>Vierta el óxido de zinc y el almidón en un recipiente de plástico, mezcle con la ayuda de una cuchara. Después, agregue la vaselina y continúe agitando. Coloque la cera en un recipiente de vidrio en baño María. Cuando se derrita la cera, incorpore la fragancia de uso cosmetico y agita con una cuchara hasta que este bien incorporado la fragancia y la cera, efectue ese procedimeinto sin retirar del baño de Maria.</p><p> Por último, incorpore la mezcla del oxido de zinc y almidon al recipiente que esta en el baño de maria y agite durante un tiempo necesario para unir y homogenizar.</p><h4>Envasado y conservación: </h4><p>Retire la mezcla del baño María y viértala de inmediato en el envase de desodorante de barra y tápelo. Debe tener cuidado de no moverlo hasta que solidifique (alrededor de 2 horas). Guárdelo en un lugar fresco y seco. No olvide colocarle una etiqueta con el nombre del producto, la fecha de elaboración y caducidad.</p><h4>Caducidad:</h4><p>El desodorante bien tapado tiene una duración aproximada de seis meses.</p><br><div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Procurar siempre utilizar la tecnica de Baño de Maria, de esa manera evita que su preparacion se queme.</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function pino_limon() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(1.5);
    componente['45'].v = p(95.75);
    componente['44'].v = p(0.375);
    componente['46'].v = p(2);
    componente['34'].v = p(0.05);
    componente['07'].v = p(0.05);
    componente['08'].v = p(0.275);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['45'].n + componente['45'].v + lili +
        componente['44'].n + componente['44'].v + lili +
        componente['46'].n + componente['46'].v + lili +
        componente['34'].n + componente['34'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + componente['08'].l + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>En lugar del Detersin puede usar Genapol o Etoxil (la misma cantidad). </p><ol><li>En un envase de plástico mezcle la trementina con el pino y el formol.</li><li>En otro envase con una parte del agua añada el detersin o el genapol/Etoxil y colorante,  mover hasta que este bien emulsionado.</li><li>En el envase donde esta el resto de agua añada las dos mezclas y debe batir hasta que quede bien homogenizado.</li><li>Estando todos lo productos juntos y homogenizado agregar el perfume y envasarlo.</li></ol>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function cera_para_pisos_autobrillante() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(75);
    componente['43'].v = p(15);
    componente['44'].v = p(6);
    componente['07'].v = p(2);
    componente['08'].v = p(5);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['43'].n + componente['43'].v + lili +
        componente['44'].n + componente['44'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>Se calienta el agua hasta unos 70ºC aproximadamente, se agrega la cera K.L.E. y se revuelve hasta que esté totalmente disuelta, se agrega la trementina de pino y se continúa calentando y revolviendo hasta su punto de ebullición, luego se retira del fuego y se continua revolviendo esporádicamente hasta dejar enfriar.</p><h4>Envasado y conservación:</h4><p>Con la ayuda del embudo se vacía la mezcla anterior a un envase limpio de plástico y se tapa. No olvide colocarle una etiqueta con el nombre del producto, la fecha de elaboración y la caducidad. Se guarda en un lugar fresco y seco.</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function suavizante_para_telas() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(91.8);
    componente['01'].v = p(3);
    componente['02'].v = p(3);
    componente['03'].v = p(0.5);
    componente['05'].v = p(0.5);
    componente['06'].v = p(0.2);
    componente['07'].v = p(0.7);
    componente['08'].v = p(0.3);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['01'].n + componente['01'].v + lili +
        componente['02'].n + componente['02'].v + lili +
        componente['03'].n + componente['03'].v + lili +
        componente['05'].n + componente['05'].v + lili +
        componente['06'].n + componente['06'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + componente['07'].s + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + componente['08'].s + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <ol> <li>Se vierte el alcohol cetílico y el agua en el recipiente de vidrio y se coloca a baño María, con la ayuda de la cuchara se agita la mezcla, hasta que se funda el alcohol cetílico.</li><li>Posteriormente se agrega el aceite de ricino sin dejar de mover hasta que se disuelva, durante 10 segundos.</li><li>Se retira el baño María y poco a poco se agrega la carboximetilcelulosa agitando continuamente hasta que se incorpore.</li><li>Por último se agrega en pausas el lauril sulfato trietanolamina, el colorante, el vinagre y la esencia, agitando durante 10 segundos entre cada uno de ellos.</li><li>Finalmente se deja enfriar a temperatura ambiente.</li></ol><h4>ENVASADO Y CONSERVACIÓN:</h4>Con la ayuda del colador y el embudo se vacía la mezcla anterior al envase limpio de plástico con capacidad de 1 litro y se tapa. Se coloca la etiqueta con el nombre del producto, fecha de elaboración y de caducidad. Se guarda en un lugar seco y fresco, fuera del alcance de los niños.<h4>CADUCIDAD:</h4>El suavizante para telas tiene una caducidad de 6 meses.<h4>MODO DE USO:</h4>Se vierte 1 cucharada sopera de suavizante (10 ml) para telas por cada dos litros de agua última de enjuague de su ropa y se tiende.<h4>DATO INTERESANTE:</h4>Un suavizante contiene; tensoactivos (agentes de limpieza o espumantes), impulsores (bossters o estabilizadores de espuma), aditivos especiales (fijadores de aroma) y secuestrantes (no permiten la acumulación de residuos), modificadores de la viscosidad (agentes espesantes o fluidificantes), agentes opalecentes o clarificantes, perfume, colorante y estabilizadores (agentes antioxidantes y absorbentes de rayos ultravioleta)<h4>BENEFICIO:</h4>Al elaborar usted mismo el suavizante para telas, podrá obtener un ahorro del 60% con relación al producto comercial.<h4>RECOMENDACIONES:</h4><ol><li>Durante la elaboración de este producto use guantes y cubrebocas.</li><li>Puede reutilizar el envase de suavizante limpio y con tapa.</li><li>En caso de que sobren ingredientes, se etiquetan y se guardan en sus respectivos envases y se conservan bien tapados en un lugar seco lejos del alcance de los niños.</li></ol>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function shampoo_pert_plus() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['09'].v = p(93);
    componente['10'].v = p(0.5);
    componente['11'].v = p(0.3);
    componente['12'].v = p(1.3);
    componente['13'].v = p(1.27);
    componente['14'].v = p(0.1);
    componente['15'].v = p(2.5);
    componente['16'].v = p(0.5);
    componente['07'].v = p(0.03);
    componente['08'].v = p(0.5);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['09'].n + componente['09'].v + lili +
        componente['10'].n + componente['10'].v + lili +
        componente['11'].n + componente['11'].v + lili +
        componente['12'].n + componente['12'].v + lili +
        componente['13'].n + componente['13'].v + lili +
        componente['14'].n + componente['14'].v + lili +
        componente['15'].n + componente['15'].v + lili +
        componente['16'].n + componente['16'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + componente['07'].p + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + componente['08'].p + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> Tome en cuenta que para la preparacion de " + UI + tipoproducto + fo + " se requieren " + componente['09'].v + " de Shampoo Base, Detallado en este Manual como <b>BASE PARA SHAMPOO</b> <h3>UNIDADES DE MEDIDAS UTILIZADAS:</h3><p>Este producto posee ingredientes que son liquidos y que la unidad de medida utilizada en la formula son unidades de peso, en ese caso usted debera utilizar la cantidad requerida pesando los ingredientes.</p><h4>PÁSOS:</h4> <li>Mezclamos estos ingredientes con la <b>BASE PARA SHAMPOO</b>, revolvemos bien hasta lograr que los productos queden bien mezclados y se vean homogeneos y por ultimo envasamos.<h4>RECOMENDACIONES:</h4> Se recomienda colocar fecha de fabricacion del producto y calcular aprox 1 año calendario para su caducidad";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function shampoo_para_perros() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(15);
    componente['17'].v = p(5);
    componente['02'].v = p(5);
    componente['18'].v = p(7.5);
    componente['01'].v = p(60);
    componente['19'].v = p(7.5);
    componente['08'].v = p(5);
    componente['20'].v = p(0.2);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['17'].n + componente['17'].v + lili +
        componente['02'].n + componente['02'].v + lili +
        componente['18'].n + componente['18'].v + lili +
        componente['01'].n + componente['01'].v + lili +
        componente['19'].n + componente['19'].v + " o " + componente['20'].n + componente['20'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>En un tazón preferiblemente de vidrio vierta el agua, la glicerina y el alcohol cetílico. Póngalo a calentar a baño María. Cuando el alcohol cetílico se funda añada el lauril sulfato de sodio, agitando muy suavemente para no formar espuma. Una vez que se emulsione el alcohol cetílico, retire la mezcla del fuego y deje enfriar.</p> <p>En otro tazón mezcle, sin agitar demasiado, el texapón y la creolina o el amitraz.</p><p>Vierta lentamente la mezcla del agua en la del texapón con creolina o el Amitraz.</p><p>Vacíe el champú en la botella de plástico con ayuda del embudo. Tape perfectamente.</p><h4>RECOMENDACIONES:</h4><p>Elabore este Shampoo en un lugar muy ventilado y use guantes y cubreboca, pues aunque el lauril sulfato de sodio no es tóxico, epero si muy irritante para las vías respiratorias que provoca tos y estornudos.</p><p>La creolina y el amitraz son aditivos que por su naturaleza contienen compuestos tóxicos y sustancias aromáticas volátiles muy impregnantes. Por ello, no elabore este producto en los mismos utensilios que destina para los alimentos.</p><p>Reutilice una botella de champú vacía para envasar el producto.</p><p>Asegúrese de mantener bien cerrado el envase de creolina para evitar que se evapore.</p><h4>ACERCA DEL BAÑO:</h4><p>Bañe a su perro cuando menos una vez al mes; hacerlo con demasiada frecuencia es contraproducente porque al quitarle la grasa natural le reseca la piel y el pelo.</p><p>Enjuague con agua abundante a su mascota, frotando con firmeza el cuero cabelludo para que no le queden residuos de champú que le resequen o irriten la piel.</p><p>Si seca a su perro con secadora, ponga ésta en el nivel más bajo y con aire tibio. No la dirija directamente a la piel ni la mantenga estática en una sola zona, pues podría causarle quemaduras.</p><h4>DATOS DE INTERES:</h4><p>La creolina es un derivado del petróleo que se ha usado desde hace muchos años para “cubrir” el hedor de las coladeras. Entre otras aplicaciones se usa para limpiar los sitios donde las mascotas suelen hacer sus necesidades.</p><p>Aunque la creolina es una sustancia antiséptica debido a su naturaleza química, es tóxica, por eso no es conveniente usarla para desinfectar heridas ni almacenarla en sitios donde haya alimentos para consumo humano o animal.</p><p>El alcohol cetílico es una sustancia sólida a temperatura ambiente. En esta tecnología tiene la función de acondicionar el pelo del animal, facilitando el cepillado, ya que lo desenreda con mayor facilidad.</p><br><div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Las concentraciones del AMITRAZ son muy variadas, tome en cuenta que para la formula indicada se esta empleando amitraz concentrado al 12%.</div><br><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Siempre procurar protegerse de los vapores que emiten algunos de los productos empleados, en especial el Amitraz, <b> MANIPULE CON MUCHA PRECAUCION.</b></div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function shampoo_pantene() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['09'].v = p(85);
    componente['12'].v = p(0.825);
    componente['13'].v = p(0.825);
    componente['16'].v = p(0.4);
    componente['10'].v = p(0.5);
    componente['21'].v = p(7.5);
    componente['15'].v = p(5);
    componente['14'].v = p(0.075);
    componente['22'].v = p(30);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['09'].n + componente['09'].v + lili +
        componente['12'].n + componente['12'].v + lili +
        componente['13'].n + componente['13'].v + lili +
        componente['16'].n + componente['16'].v + lili +
        componente['10'].n + componente['10'].v + lili +
        componente['21'].n + componente['21'].v + lili +
        componente['15'].n + componente['15'].v + lili +
        componente['14'].n + componente['14'].v + lili +
        componente['22'].n + componente['22'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>Tome en cuenta que para la preparacion de " + UI + tipoproducto + fo + " se requieren " + componente['09'].v + " de Shampoo Base Detallado en este Manual como <b>BASE PARA SHAMPOO</b></p> <h3>UNIDADES DE MEDIDAS UTILIZADAS:</h3>p>Este producto posee ingredientes que son liquidos y que la unidad de medida utilizada en la formula son unidades de peso, en ese caso usted debera utilizar la cantidad requerida pesando los ingredientes.</p><h4>PÁSOS:</h4> <li>Mezclamos estos ingredientes con la <b>BASE PARA SHAMPOO</b>, revolvemos bien hasta lograr que los productos queden bien mezclados y se vean homogeneos y por ultimo envasamos.<h4>RECOMENDACIONES:</h4> <p>Se recomienda colocar fecha de fabricacion del producto y calcular aprox 1 año calendario para su caducidad.</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function limpia_vidrio() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['23'].v = p(0.55);
    componente['27'].v = p(3);
    componente['00'].v = p(95.3);
    componente['25'].v = p(0.15);
    componente['26'].v = p(1);
    componente['07'].v = p(1.5);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['23'].n + componente['23'].v + lili +
        componente['27'].n + componente['27'].v + lili +
        componente['00'].n + componente['00'].v + lili +
        componente['25'].n + componente['25'].v + lili +
        componente['26'].n + componente['26'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>La elaboracion de este producto es bastante sencilla, trata de mezclar los componentes y envasar la preparacion, el orden de mezcla es el siguiente:</p> <ol> <li>Agregar el agua sobre el Nonil fenol, en pequeñas porciones hasta obtener una solución uniforme. </li> <li>Añadir el hidróxido de amonio.</li> <li>Agregar el alcohol isopropílico.</li><li>Agregar el butil cellosolve tambien conocido como Glicoéter EB, Mono butil eter del etilenglicol, Butil oxitol. CAS.</li><li>Agregar el colorante.</li><li>Envasar.</li> <h4>Recomendaciones:</h4> Durante la elaboración de este producto use guantes y cubrebocas. En caso de sobrar ingredientes, consérvelos en sus envases originales, etiquete y déjelos en un lugar fresco y seco, fuera del alcance de los niños, para que los vuelva a utilizar en la preparación de más producto. Es importante lavar muy bien los utensilios empleados, con jabón y de preferencia con agua tibia.";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function gel_fijador_de_cabello() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(57);
    componente['28'].v = p(23);
    componente['29'].v = p(4.5);
    componente['27'].v = p(14.5);
    componente['07'].v = p(0.3);
    componente['08'].v = p(0.3);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['28'].n + componente['28'].v + lili +
        componente['29'].n + componente['29'].v + lili +
        componente['27'].n + componente['27'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <ol> <li>Vierta el agua en el recipiente con capacidad del triple de la preparacion e incorpore con la ayuda del colador, poco a poco (el carbopol), agitando al mismo tiempo con la cuchara hasta disolverlo. </li> <li>Añada la trietanolamina, mezclando para incorporar, después con la ayuda de la batidora a velocidad baja o el tenedor, bata hasta formar una mezcla homogénea (aproximadamente 1-3 minutos segun la cantidad de producto).</li> <li>Después, sin dejar de batir, agregue el alcohol, poco a poco el colorante y la esencia, hasta obtener el tono y aroma deseado, formando así el gel (durante 1-2 minutos).</li> </ol> <h4>Envasado y conservación:</h4><p> Con la ayuda de la cuchara, vierta la mezcla en el recipiente para envasar, tápelo. Adhiera la etiqueta con el nombre del producto, fecha de elaboración y de caducidad, para conservarse en un lugar fresco. </p><h4>Caducidad:</h4> <p>El gel fijador para cabello, conserva sus características propias de uso, hasta por 3 meses. </p><h4>Modo de uso: </h4> <p>Aplique el gel sobre el cabello seco o mínimamente húmedo. Dato interesante: Un gel es un sólido elástico, el cual envuelve y atrapa el agua en una red tridimensional, que se forma por las mismas partículas en suspensión. </p><h4>Beneficio:</h4> <p>Al elaborar el gel, podrá obtener un ahorro hasta del 60% con relación al producto comercial, además de poder variar la fragancia de su preferencia.</p> <h4>Recomendaciones:</h4> <p>Durante la elaboración de este producto use guantes y cubrebocas. En caso de sobrar ingredientes, consérvelos en sus envases originales, etiquete y déjelos en un lugar fresco y seco, fuera del alcance de los niños, para que los vuelva a utilizar en la preparación de más gel. Es importante lavar muy bien los utensilios empleados, con jabón y de preferencia con agua tibia.</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function cloro() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(80);
    componente['30'].v = p(20);
    componente['31'].v = p(0.25);
    componente['32'].v = p(0.25);
    componente['08'].v = p(0.30);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['30'].n + componente['30'].v + lili +
        componente['31'].n + componente['31'].v + lili +
        componente['32'].n + componente['32'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>En el recipiente plástico se debe colocar el agua, al cual le agregan el hipoclorito de sodio y revuelven bien con la cuchara de palo (ambos elementos los pueden medir con una probeta). Luego agregamos el fosfato trisódico y el carbonato- Previamente pesados, y deben seguir revolviendo muy bien. Adicionan la fragancia hiposódica previamente medida en la probeta, se revuelve bien todo con la cuchara de palo y empacamos con la ayuda del embudo.</p> <p><b>Nota:</b> La fragancia hiposódica que puede ser usada: Lavanda, Floral y Limón, siempre y cuando sean fragancias hiposódicas. </p> <p><b>Recomendaciones:</b> No tiene ninguna recomendación en especial. </p><p><b>Precauciones:</b> Use delantal, evite las salpicaduras mientras hace el producto, pues este producto puede decolorar la ropa.</p><br><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Este producto se recomienda sea preparado en un lugar abierto o ventilado ya que los vapores que emite el hipoclorito pueden causar asfixias.</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function desinfectante_para_pisos() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(97.5);
    componente['23'].v = p(0.75);
    componente['33'].v = p(0.75);
    componente['34'].v = p(0.075);
    componente['24'].v = p(0.075);
    componente['07'].v = p(0.1);
    componente['08'].v = p(0.75);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['23'].n + componente['23'].v + lili +
        componente['33'].n + componente['33'].v + lili +
        componente['34'].n + componente['34'].v + lili +
        componente['24'].n + componente['24'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <ol> <li>Mezclar la Fragancia y el Nonil Fenol </li> <li>En el agua Agregar todos los otros componentes.</li> <li>Por ultimo unificar todos los productos en un solo envase, remover muy bien y dejar reposar si se ha generado mucha espuma.</li> </ol><h4>Nota:</h4><p>Sientase en libertad de agregar mas fragancia o mas color, esta practica no es tan adecuada ya que no es deseable un producto que en vez de limpiar manche las superficies donde se aplique.</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function detergente_para_ropa() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(85);
    componente['35'].v = p(10);
    componente['28'].v = p(2);
    componente['36'].v = p(3);
    componente['08'].v = p(0.05);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['35'].n + componente['35'].v + lili +
        componente['28'].n + componente['28'].v + lili +
        componente['36'].n + componente['36'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO</h3><ol><li>Calentar el agua sin dejar hervir, pasarla a un recipiente plastico destinado a la mezcla y preparacion del detergente, disolver el hidroxido de sodio o soda caustica alli en el agua revolviendo con cuidado de no salpicar al colocar las escamas en el agua.</li><li>Hacer la aplicación de la fragancia que haya escogido para esta preparacion.</li><li>Dejar enfriar y aplicar el acido sulfonico agitando suavemente para ayudar a la disolucion de estos componentes.</li><li>Adicionar la TEA agitando muy bien hasta ver la mezcla homogeneamente mezclada.</li><li>Coloree con un pigmento que sea acorde con la fragancia y al gusto de quien utilizara este producto </li><li>Empacar en recipiente con tapa y si es posible deje el producto en reposo las siguientes veinticuatro horas antes de ser utilizado. </li></ol><h3>RECOMENDACION: </h3><p>Este producto es un Jabon con altas propiedades desengrasantes, para uso domestico o industrial, altamente recomendado para el cuidado del hogar en los pisos y baños, cuando estos jabones liquidos son preparados para la limpieza de cocinas industriales y restaurantes, puede disminuirse u omitirse la fragancia, sin que esto altere la calidad en su poder limpiador.</p><br><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Para la elaboracion de este producto debe tomar en cuenta que la soda cautica genera vapores al ser manipulada tome en cuenta hacer la preparacion en un lugar abierto o con ventilacion, <b>MANIPULE CON MUCHA PRECAUCION</b> utilizando para ello implementos de proteccion adecuados.</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function lavaplatos_liquido() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(68);
    componente['37'].v = p(9);
    componente['18'].v = p(18);
    componente['11'].v = p(3.5);
    componente['07'].v = p(0.1);
    componente['08'].v = p(0.3);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['37'].n + componente['37'].v + lili +
        componente['18'].n + componente['18'].v + lili +
        componente['11'].n + componente['11'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO</h3><ol><li>Disolver el LAS 50 en 40 litros d agua y reposar 4 horas, y este bien diluido.</li><li>Agregar poco a poco el genapol liquido y mover, dejar reposar 4 horas mas, estas 4 horas si son muy importantes.</li><li>Luego el agua con sal se agrega poco a poco para espesar (el espesor lo decide usted).</li><li>Agregar la fragancia y el color.</li></ol><h4>RECOMENDACION: </h4><p>Este producto es un Jabon con propiedades desengrasantes, para uso domestico o industrial, altamente recomendado para el cuidado de vidriería como platos, vasos, vajilla y cubiertos en general, ideal para el uso en las industrias y restaurantes, puede disminuirse u omitirse la fragancia, sin que esto altere la calidad en su poder limpiador.</p><h4>NOTA:</h4> La Sal se recomienda Diluir 1Kg en 2.5 Litros de Agua y utilizar esta salmuera para espesar el lavaplatos, sin exceder el 3.5% de la cantidad a preparar para evitar que se dañe la preparación, puede ir agregando de a poco hasta obtener un espesor adecuado.</p><br><div class='alert alert-warning' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Si se llega a pasar en la cantidad de sal, el producto tomara un aspecto lechozo, no se preocupe ya que al incorporar mas producto sin espesar volvera al tono y consistencia adecuada.</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function base_para_shampoo() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['37'].v = p(10);
    componente['18'].v = p(2.5);
    componente['11'].v = p(4.167);
    componente['00'].v = p(99);
    componente['39'].v = p(0.058);
    componente['40'].v = p(0.058);
    componente['41'].v = p(0.042);
    componente['42'].v = p(0.025);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['37'].n + componente['37'].v + lili +
        componente['18'].n + componente['18'].v + lili +
        componente['11'].n + componente['11'].v + lili +
        componente['00'].n + componente['00'].v + lili +
        componente['39'].n + componente['39'].v + lili +
        componente['40'].n + componente['40'].v + lili +
        componente['41'].n + componente['41'].v + lili +
        componente['42'].n + componente['42'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3><p>En un recipiente, se llena hasta la mitad de agua, luego agregamos el Texapon al 70 (Si utiliza Texapon al 40 al final esta la proporcion a utilizar) A este componente tambien se le conoce como: Lauril Sulfato de Sodio o Genapol, este componente y lo diluimos en el agua con el uso de una paleta hasta que éste desaparezca y la mezcla quede sin residuo, una vez logrado esto le agregamos el Comperland K-D (El Comperland es el mismo cocoamida, es un espesante) y revolvemos suavemente con un utensilio de madera o P.V.C. hasta lograr que los productos queden bien mezclados.<br>Después en otro recipiente plástico aparte, agregamos 1/4 del agua que nos queda, más el Cloruro de Sodio, el Ácido Cítrico, el Äcido Bórico y revolvemos bien esta mezcla. Luego en otro recipiente plástico aparte, agregamos el restante del agua más el Metil Parabeno y el Metil Parabeno Sódico y revolvemos bien esta mezcla , por último tomamos las dos últimas mezclas y se la agregamos a la primera mezcla bajo agitación continua hasta que alcance el punto de espesos deseado. A esta mezcla se le llama base de shampoo.<br> <p><b>NOTA:</b> Si a la base de shampoo le falta más viscosidad o espesor le agregamos otro poquito de Cloruro de Sodio (sal) proporcionalmente (o sea en pequeñas cantidades). Para medir el grado o el PH de la base de Shampoo, utilizamos un Peachimetro de 1 a 10 ó de 1 a 11 o un termómetro de grados para productos químicos. Y el grado de la base deberá ser 5 grados mínimos hasta 8 grados máximos. Para probar a esta mezcla, el certificado de análisis, introducimos en la base el Peaclimetro o el Termómetro. Y nos dará el resultado deseado. Si la base queda subida de grados fue porque le echó más de la cuenta de los químicos a la formulación. Para no perder la base, le agregamos cualquiera de los siguientes componentes: Trictalonamina o Ácido Cítrico en pequeñas cantidades hasta observar que la base baje de grados. Ya que si el producto está alterado de PH, puede causar la caída del cabello de las personas.<br> O si la base queda rebajada de grados, fue por que le echó menos de la cuenta de químicos a la formulación, dejarla la base así, no tiene ningún problema al usarlo en el cuero cabelludo, el certificado de análisis que expiden del producto es que de baja calidad.<br> </p><p><b>NOTA:</b> Cuando en la fórmula utilicemos Texapon 70 hay que diluirlo, si es Texapon 40, no hay necesidad de diluirlo ya que este producto es líquido</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function detersin_k() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['35'].v = p(19.01);
    componente['73'].v = p(3.28);
    componente['74'].v = p(1.38);
    componente['60'].v = p(0.01);
    componente['11'].v = p(0.17);
    componente['65'].v = p(0.05);
    componente['00'].v = p(76.09);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['35'].n + componente['35'].v + lili +
        componente['73'].n + componente['73'].v + lili +
        componente['74'].n + componente['74'].v + lili +
        componente['60'].n + componente['60'].v + lili +
        componente['11'].n + componente['11'].v + lili +
        componente['65'].n + componente['65'].v + lili +
        componente['00'].n + componente['00'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> PARA LA ELABORACIÓN DEl DETERSIN K, DETERGENTE BIODEGRADABLE,  USE GUANTES, LENTE DE SEGURIDAD, MASCARILLA Y  DELANTAL DE PROTECCIÓN.<h4>INDICACIONES</h4><ol>    <li>En un recipiente de plástico resistente agregar la mitad del agua y la mitad de la soda cáustica agitando fuertemente con una paleta de madera.</li>    <li>Agregar el acido oxálico agitando el producto hasta que se disuelva, adicione acido sulfonico de a poco agitando con la paleta hasta que homogenice.</li>    <li>Agregar el resto de agua, mas la urea y el cloruro de sodio con el EDTA, se agita hasta lograr una buena homogenización deje reposar unos minutos.</li>    <li>Agregue el resto de la soda cáustica, el ph de este producto debe estar entre 7.00 y 8.00 (alcalino), para regular el ph debe agregar soda cáustica, para esta tarea debe ayudarse con un PHMETRO</li><li>El producto final es de un color amarillo muy claro y transparente.</li> </ol><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Siempre procurar protegerse la mayor parte de la piel expuesta, este producto se debe preparar en un lugar abierto y con proteccion en la mayor parte del cuerpo, <b> TENGA SUMA PRECAUCION.</b></div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }

function acido_para_pisos_de_granito() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(92.00);
    componente['60'].v = p(3.00);
    componente['18'].v = p(5.00);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['60'].n + componente['60'].v + lili +
        componente['18'].n + componente['18'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>En un recipiente plástico de amplia capacidad colocar el agua, a esa agua debe agregar de Acido Oxálico. Se debe remover constantemente muy bien, luego le agregamos el Genapol o Detersin. Revolver bien todo con la cuchara de palo y empacar con la ayuda del embudo.</p>   <h4> Precauciones:</h4> <p>Tenga presente que esta trabajando con ácidos, evite el contacto con los ojos o la inhalación e ingestión de estos.</p><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Siempre procurar protegerse la mayor parte de la piel expuesta, este producto se debe preparar en un lugar abierto y con proteccion en la mayor parte del cuerpo, <b> TENGA SUMA PRECAUCION.</b></div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}

function talco_para_pies() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['57'].v = p(71.84);
    componente['42'].v = p(23.79);
    componente['61'].v = p(1.46);
    componente['62'].v = p(1.46);
    componente['08'].v = p(1.46);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['57'].n + componente['57'].v + lili +
        componente['42'].n + componente['42'].v + lili +
        componente['61'].n + componente['61'].v + lili +
        componente['62'].n + componente['62'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>En un recipiente plástico de amplia capacidad colocar el talco neutro, recomendamos usar talco importado de buena calidad, hay que tamizarlo un par de veces, luego le le incorporael Acido Borico (previamente pulverizado y tamizado), proceda a revolver bien con una cuchara de palo. Luego se le incorporael Alcanfor previamente pulverizado en el mortero, y lo revolvemos muy bien. Luego se le agregala Cumarina previamente pulverizadas en el mortero, y revolvemos muy bien. Ya por ultimose le agrega la fragancia.</p><p>La fragancia se le adiciona al recipiente plástico revolviendo muy bien todo el tiempo, y por ultimo empacamos en las vasijas plasticas propias para el talco con la ayuda del embudo.</p><h4>Recomendaciones:</h4><ul><li> En el talco para los pies, es importante utilizar el talco importado Americano, y como recomendación se debe utilizar fragancia para aromatizar el talco en lugar del mentol, ya que el mentol enfría mucho los pies.</li><li> Si realizaras poca cantidad de " +
        fo + " Una forma fácil de hacer esta mezcla es utilizando una bolsa plástica grande, verificando que no esté rota.</li><li>Como el alcanfor es un material que siempre trata de compactarse, para evitar que esto suceda, en una bolsa pequeña aparte se coloca el alcanfor a utilizar y le agrega dos o tres cucharadas de la mezcla entre el talco importado y el ácido bórico para que permita un mejor pulverizado.</li></ul><p>El Talco importado, es un suavizante para la piel.</p><p> El ácido Bórico, elimina el mal olor, sirve de bactericida (anti hongos)</p><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Siempre procurar proteger las vias respiratorias y los ojos, Se recomienda el uso de Tapabocas y Lentes de Seguridad. <b> TENGA SUMA PRECAUCION.</b></div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}

function jabon_liquido_para_el_cuerpo() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(70.42);
    componente['07'].v = p(0.09);
    componente['40'].v = p(0.19);
    componente['08'].v = p(0.94);
    componente['63'].v = p(4.69);
    componente['18'].v = p(14.08);
    componente['64'].v = p(3.76);
    componente['17'].v = p(1.88);
    componente['65'].v = p(0.19);
    componente['38'].v = p(3.76);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['40'].n + componente['40'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + lili +
        componente['63'].n + componente['63'].v + lili +
        componente['18'].n + componente['18'].v + lili +
        componente['64'].n + componente['64'].v + lili +
        componente['17'].n + componente['17'].v + lili +
        componente['65'].n + componente['65'].v + lili +
        componente['38'].n + componente['38'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3>" + olli + "En un recipiente plástico colocar el agua, al cual le debe agregar el Metil Parabeno Sódico, proceda a revolver bien con la cuchara de palo." + lili + " Luego le agregamos el Anfótero o Probetaina, el Genapol, el Nacarado, y la Glicerina y revolvemos muy bien." + lili + "En un segundo recipiente plástico debe mezclarse el Edta con la anilina." + lili + " Esta mezcla se le adiciona al recipiente plástico donde se tiene la primera mezcla revolviendo muy bien. " + lili + "Luego le agregamos poco a poco el Coperlan revolviendo continuamente hasta que espese." + lili + " Luego le agregamos la fragancia. Revolvemos bien todo con ayuda de una cuchara de palo y empacamos con la ayuda del embudo." + liol + "<h4> Recomendaciones:</h4> <p>En la mezcla del jabón líquido para baño, la anilina y el Edta se mezclan por aparte y luego se adicionan a la mezcla principal. Cuando se vaya a mezclar el Comperlan, se debe echar poquito a poquito, hasta encontrar el grosor ideal del shampoo, en ocasiones no es necesario aplicar completo la cantidad de Comperlan de la formula, esto motivado a la calidad y concentracion del mismo como materia prima. Sí el shampoo le quedó muy grueso, aplique un poco de Propilen Glicol para adelgazarlo. El Anfótero ó Profetaina es la que se encarga de producir la espuma</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}

function aceite_para_bebe() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['66'].v = p(1.00);
    componente['04'].v = p(98.5);
    componente['08'].v = p(0.50);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['66'].n + componente['66'].v + lili +
        componente['04'].n + componente['04'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + componente['08'].tal + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3><p>En un recipiente plástico colocar el Aceite Mineral, al cual le agregamos el Propilen Glicol, proceda a revolver bien con la cuchara de palo. Luego le agregamos la fragancia, y se revuelve muy bien. Se deja reposar por unos 5 minutos dejando que se asiente. Y empacamos con la ayuda del embudo.</p><h4>Recomendaciones:</h4><p> Cuando se termine la mezcla de la preparación del aceite, al empacar el producto, el residuo de la preparación no se debe empacar, ya que estos son la precipitación de elementos orgánicos (Residuos orgánicos que contenía el aceite).</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}

function removedor_de_esmalte() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['07'].v = p(1.00);
    componente['17'].v = p(2.00);
    componente['67'].v = p(59.94);
    componente['04'].v = p(3.00);
    componente['68'].v = p(34.97);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['17'].n + componente['17'].v + lili +
        componente['67'].n + componente['67'].v + lili +
        componente['04'].n + componente['04'].v + lili +
        componente['68'].n + componente['68'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3><p>En un primer recipiente plástico colocar el Aceite Mineral y el Acetato de Butílo (Acetato de Isobutílo), proceda a revolver bien con la cuchara de palo. En un segundo recipiente plástico colocar el Alcohol y la Glicerina, proceda a revolver bien con la cuchara de palo. En un tercer recipiente de plástico pequeño (Beakers), colocamos un poco de agua y se le disuelve la Anilina color rosa. De esta preparación le aplicamos unas cuantas gotas segun la cantidad e intensidad deseada al segundo recipiente para darle un tono pastel a la mezcla. Por ultimo se le agrega el contenido del primer recipiente al segundo recipiente, y se revuelve muy bien. Y empacamos con la ayuda del embudo.</p><h4> Recomendaciones:</h4> " + ulli + "En el primer recipiente se mezcla el Acetato de Burilo y el Aceite Mineral y en el segundo recipiente se mezcla el alcohol y la glicerina (protege la cutícula)." + lili + "En poca agua se disuelve el color rosa y se le hecha al segundo recipiente hasta que dé un tono claro pastel." + lili + "Luego se vierte el contenido del primer recipiente al contenido del segundo recipiente." + liul;

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}

function jabon_liquido_para_las_manos() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['00'].v = p(71.03);
    componente['07'].v = p(0.10);
    componente['28'].v = p(0.30);
    componente['08'].v = p(0.99);
    componente['18'].v = p(4.76);
    componente['17'].v = p(2.88);
    componente['65'].v = p(0.20);
    componente['69'].v = p(0.30);
    componente['45'].v = p(18.95);
    componente['06'].v = p(0.50);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['07'].n + componente['07'].ext + componente['07'].v + lili +
        componente['28'].n + componente['28'].v + lili +
        componente['08'].n + componente['08'].ext + componente['08'].v + lili +
        componente['18'].n + componente['18'].v + lili +
        componente['17'].n + componente['17'].v + lili +
        componente['65'].n + componente['65'].v + lili +
        componente['69'].n + componente['69'].v + lili +
        componente['45'].n + componente['45'].v + lili +
        componente['06'].n + componente['06'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3><p>En un recipiente plástico colocar el agua, al cual le agregamos el Benzoato de Sodio, proceda a revolver muy bien. Luego se agrega el CMC, y se procede a revolver continuamente con la cuchara de palo. Luego le agregamos la Trietanolamina. Y se contina revolviendo muy bien hasta que espese. Luego agregamos el Detersin, no se debe dejar de revolver muy bien. Luego agregamos el Genapol, Luego agregamos la Glicerina, En un segundo recipiente (Beakers) plástico graduados para líquidos ponemos un poquito de agua y mezclamos el Edta con la anilina. Y se le adiciona a la mezcla principal y se revuelve muy bien. Y se le adiciona a la mezcla principal la fragancia, revolviendo muy bien. Por ultimo empacamos con la ayuda del embudo.<h4>Recomendaciones:</h4><p> En la preparación del jabón liquido para manos se hace la mezcla de los productos (agua, Benzoato de Sodio, Metil Celuloso, Trietanolamina, Detersin, Genapol y la glicerina), de uno en uno. Teniendo en cuenta que una vez se aplique el Metil Celuloso hay que mezclar continuamente por un tiempo de 5 minutos, al igual que al usar la Trietanolamina se sigue revolviendo hasta que la mezcla espese,</p><p>Después en un poquito de agua se hace una mezcla separada entre la anilina y el Edta  y se le adiciona a la mezcla principal. Por último la fragancia se le aplica directamente a la mezcla principal.</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}

function perfumes() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['75'].v = p(67.31);
    componente['66'].v = p(1.92);
    componente['71'].v = p(28.85);
    componente['72'].v = p(1.92);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['75'].n + componente['75'].v + lili +
        componente['66'].n + componente['66'].v + lili +
        componente['71'].n + componente['71'].v + lili +
        componente['72'].n + componente['72'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3><p>En el envase que va a servir para el empaque de la loción, mezcle el fijador PPG 20 o Fijador AX con el Extracto del perfume, y agite muy bien. En otro recipiente plástico, mezcle el Alcohol Fino para Perfumería con el Propilen Glicol, y agite muy bien. Vierta el contenido de esta segunda mezcla al contenido de la primera mezcla. Agite ambas mezclas para que estas emulsionen perfectamente. Coloque el producto terminado por 24 horas en la nevera, heladera o refrigerador. Procure que el envase este completamente forrado con una bolsa oscura o negra para que evite que la luz penetre a la botella. Si la loción queda turbia cuélela con el papel filtro o media velada. Deje la loción a temperatura ambiente en un lugar oscuro por espacio de 12 horas para que macere.</p><h4>Recomendaciones:</h4> " + ulli + " La preparación de las lociones se hace en el mismo envase que va a servir de empaque. " + lili + "Se debe respetar el orden de mezcla, es decir primero colocar el Extracto del Perfume en el recipiente, luego se le añade el Fijador y se revuelve muy bien, posteriormente en otro recipiente se coloca el Alcohol de Perfumería con el Propilen Glicol y agitar para que se mezcle bien y luego de ello es que se unen las dos mezclas virtiendo el contenido del segundo al primer recipiente" + liul + "<h4><b>NOTA:</b></h4><p> 100 c.c. equivale a 3.4 onzas y 50 c.c. equivale 1.7 onzas.</p><p>Los extractos de las marcas conocidas se consiguen en el mismo lugar donde se compran  el resto de los elementos químicos.</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}


function aceite_para_aromaterapia() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['77'].v = p(5);
    componente['76'].v = p(95);


    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['00'].n + componente['00'].v + lili +
        componente['23'].n + componente['23'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <ol> <li>Escoger el aceite esencial natural según el efecto deseado. </li> <li>Depositar el aceite esencial en un recipiente y añadir el aceite de almendras dulces, homogeneizando.</ol> <h4>Nota:</h4> <p>Sientase en libertad de agregar mas aceite esencial a su formula.</p> <h4>Formas de Uso:</h4> <p>Para un baño, se depositarán unas gotas en la bañera.</p> <p>Para una sauna, se añadirán 2-3 gotas en el recipiente interior con agua, por cada 500 cc.</p>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
    }


    function aceite_para_baño() {
        var UI = document.getElementById('entrada').value;
        if (isNaN(UI)) { return false; }
        componente['78'].v = p(25);
        componente['80'].v = p(25);
        componente['51'].v = p(29);
        componente['81'].v = p(20);
        componente['08'].v = p(0.5);
        componente['07'].v = p(0.5);

        tratamiento(UI);
        tratamiento_producto(UI);

        resul =
            tca + UI + tipoproducto + fo + tcc + olli +
            componente['78'].n.replace(" = ", "") + " o " + componente['79'].n + componente['78'].v + lili +
            componente['80'].n + componente['80'].v + lili +
            componente['51'].n + componente['51'].v + lili +
            componente['81'].n + componente['81'].v + lili +
            componente['08'].n + componente['08'].ext + componente['08'].v + lili +
            componente['07'].n + componente['07'].ext + componente['07'].v + componente['07'].grasa + liol;

        var procedimiento = "<h3>PROCEDIMIENTO:</h3> <ol> <li>Colocar al Baño María todos los ingredientes, salvo el perfume. </li> <li>Retirar del foco calorífico y perfumar.</li> <li>Por ultimo envasar.</li> </ol><h4>Nota:</h4><p>Este es un producto TIXOTRÓPICO (en el envase es consistente pero al extenderlo sobre la piel, se vuelve fluido).</p>";

        document.getElementById('salida').innerHTML = resul,
            document.getElementById('procedimiento').innerHTML = procedimiento;
            //Linea agregada
    popove();
        }


        function abrillantador_automotriz_universal() {
            var UI = document.getElementById('entrada').value;
            if (isNaN(UI)) { return false; }
            componente['509'].v = p(10);
            componente['510'].v = p(20);
            componente['23'].v = p(1);
            componente['17'].v = p(5);
            componente['34'].v = p(0.1);
            componente['00'].v = p(64);

            tratamiento(UI);
            tratamiento_producto(UI);

            resul =
                tca + UI + tipoproducto + fo + tcc + olli +
                componente['509'].n + componente['509'].v + lili +
                componente['510'].n + componente['510'].v + lili +
                componente['23'].n + componente['23'].v + lili +
                componente['17'].n + componente['17'].v + lili +
                componente['34'].n + componente['34'].v + lili +
                componente['00'].n + componente['00'].v + liol;

            var procedimiento = "<h3>PROCEDIMIENTO:</h3> <ol> <li>En un recipiente mezclar perfectamente el nonil fenol y el aceite de silicón.</li>            <li>Agregar la emulsión de silicón y vaciar el recipiente que contiene el agua total, agitar vigorosamente hasta uniformar la mezcla.</li> <li>Agregar los ingredientes restantes, efectuar una agitación vigorosa y dejar reposar para poder envasar.</li></ol><h4>Nota:</h4><p>Agitar el producto antes de usar</p><p></p>Si desea viscosidad espeso disolver en agua caliente 2-4 % de diestearato de Polietilenglicol (peg-150) y agregarlo al producto final.";

            document.getElementById('salida').innerHTML = resul,
                document.getElementById('procedimiento').innerHTML = procedimiento;
                //Linea agregada
        popove();
            }


function limpiador_de_metales_en_polvo() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['518'].v = p(15);
    componente['501'].v = p(55);
    componente['180'].v = p(5);
    componente['503'].v = p(25);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['518'].n + componente['518'].v + lili +
        componente['501'].n + componente['501'].v + lili +
        componente['180'].n + componente['180'].v + lili +
        componente['503'].n + componente['503'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>En un recipiente plástico de amplia capacidad colocar todos los ingredientes, de esta manera conseguimos un limpiador de metales económico y de muy buena calidad, que solo requiere que la cantidad que se va a utilizar al momento sea mezclado con agua hasta formar una pasta, aplicar con un trapo y limpiar. Este producto tiene la ventaja de que limpia y pule al mismo tiempo, sin rayar, ni atacar el metal.</p><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Siempre procurar proteger las vias respiratorias y los ojos, Se recomienda el uso de Tapabocas y Lentes de Seguridad. <b> TENGA SUMA PRECAUCION.</b></div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}


function desengrasante_universal() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['01'].v = p(7);
    componente['191'].v = p(7);
    componente['189'].v = p(5);
    componente['192'].v = p(3);
    componente['516'].v = p(3);
    componente['34'].v = p(0.25);
    componente['00'].v = p(74.75);
    tratamiento(UI);
    tratamiento_producto(UI);
    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['01'].n + componente['01'].v + lili +
        componente['191'].n + componente['191'].v + lili +
        componente['189'].n + componente['189'].v + lili +
        componente['192'].n + componente['192'].v + lili +
        componente['516'].n + componente['516'].v + lili +
        componente['34'].n + componente['34'].v + lili +
        componente['00'].n + componente['00'].v + liol;

    var procedimiento = "<p>Pudiendo ser considerado un detergente liquido de alta concentración, que limpia y desengrasa campanas de extracción, oro, plata, piedras preciosas, alfombras, ceramicas, etc.</p><h3>PROCEDIMIENTO:</h3> <ol><li>Calentar la mitad de agua total de la formulación donde disolveremos perfectamente el alkilbencensulfanato de sodio. </li> <li>En otro recipiente calentar la otra mitad de agua a 70°c a punto de ebullición, agregando el tripolifosfato de sodio, el cual diluiremos completamente con agitación vigorosa.</li> <li>Procedemos a mezclar ambas fases la fase 1 en la fase 2 y se debe mezclar perfectamente al agregar el resto de los ingrediente en el orden de la formulación con agitado vigoroso.</li> <li>Dejar reposar y envasar.</li></ol><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Para la elaboracion de este producto debe tomar en cuenta que se pueden generar vapores, trate de elaborar su producto en un lugar abierto o con ventilacion, <b>MANIPULE CON MUCHA PRECAUCION</b> utilizando para ello implementos de proteccion adecuados.</div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
    //Linea agregada
    popove();

}


function formula_anticorrosiva_en_aceite() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['150'].v = p(1.96);
    componente['151'].v = p(32.68);
    componente['152'].v = p(65.36);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['150'].n + componente['150'].v + lili +
        componente['151'].n + componente['151'].v + lili +
        componente['152'].n + componente['152'].v + liol;

    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <p>Este producto solo se mezclan los componentes y se procede a ser envasado.</p><div class='alert alert-danger' role='alert'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span> Evitar exposicion cerca de fuentes caloricas. <b> TENGA PRECAUCION.</b></div>";

    document.getElementById('salida').innerHTML = resul,
        document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();

}

//ACTUALIZACION 21/08/2020

function alcohol_desinfectante_en_gel() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['29'].v = p(0.8);
    componente['28'].v = p(1);
    componente['99'].v = p(0.2);
    componente['67'].v = p(83);
    componente['00'].v = p(15);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['29'].n + componente['29'].v + lili +
        componente['28'].n + componente['28'].v + lili +
        componente['99'].n + componente['99'].v + lili +
        componente['67'].n + componente['67'].v + lili +
        componente['00'].n + componente['00'].v + liol;
quitarigual();
    var procedimiento = "<h3>PROCEDIMIENTO:</h3> <ul><li><b>PASO 1: </b>Disuelva el "+componente['29'].n+" en la mezcla de "+componente['00'].n+" y "+componente['67'].n+". Se recomienda dejar el "+componente['29'].n+" en esta mezcla por 24 horas para que se disuelva fácilmente.</li><li><b>PASO 2: </b>Agregue el "+componente['99'].n+" y disuelva.</li><li><b>PASO 3: </b>Finalmente adicione la "+componente['28'].n+", disuelta en un poco de agua, haga este paso poco a poco con agitación constante. De inmediato se formará el gel.</li></ul>";

    document.getElementById('salida').innerHTML = resul,
    document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
ponerigual();

}

function blanqueador_concentrado() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['30'].v = p(24.11);
    componente['36'].v = p(0.2);
    componente['185'].v = p(0.75);
    componente['00'].v = p(74.94);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['30'].n + componente['30'].dilu13 + componente['30'].v + lili +
        componente['36'].n + componente['36'].v + lili +
        componente['185'].n + componente['185'].v + lili +
        componente['00'].n + componente['00'].v + liol;
quitarigual();
    var procedimiento = "<h3>PROCEDIMIENTO:</h3><p><b>FUNCION DEL PRODUCTO</b><br>El hipoclorito de sodio en solución, es un producto ampliamente utilizado como blanqueador, desinfectante y desodorante de malos olores. Es el producto de limpieza que más se usa por su efectividad y bajo costo. Debe utilizarse con precaución, ya que es irritante y corrosivo en las superficies metálicas.</p> <ul><li><b>PASO 1: </b>Disuelva el "+componente['30'].dilu13+" en el "+componente['00'].n+".</li><li><b>PASO 2: </b>En otro envase haga una mezcla de "+componente['36'].n+" y "+componente['185'].n+".</li><li><b>PASO 3: </b>Finalmente agregue la mezcla del <b>PASO 2</b> a la mezcla del <b>PASO 1</b>.</li></ul><p><b>INDICACIONES DE USO</b><br>El producto obtenido con este proceso debe ser diluido en una cubeta de 10 litros, 50 mililitros de blanqueador (aproximadamente un cuarto de taza) y limpie con esta solución, mosaicos y azulejos. En la lavadora vierta 1⁄2 taza de blanqueador (aproximadamente 125 ml) por cada 30 litros de agua.</p>";

    document.getElementById('salida').innerHTML = resul,
    document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
ponerigual();

}
function crema_para_limpiar_zapatos() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['49'].v = p(11.69);
    componente['100'].v = p(11.69);
    componente['50'].v = p(5.19);
    componente['51'].v = p(5.19);
    componente['527'].v = p(64.94);
    componente['91'].v = p(1.3);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['49'].n + componente['49'].v + lili +
        componente['100'].n + componente['100'].v + lili +
        componente['50'].n + componente['50'].v + lili +
        componente['51'].n + componente['51'].v + lili +
        componente['527'].n + componente['527'].v + lili +
        componente['91'].n + componente['91'].v + liol;
quitarigual();
    var procedimiento = "<h3>PROCEDIMIENTO:</h3><p> En un primer recipiente debe colocar la "+componente['49'].n+", la "+componente['50'].n+", el "+componente['100'].n+", y la "+componente['51'].n+". levamos a baño maría, y dejamos hasta que todos los ingredientes se derritan y se integren, el tiempo dependera de la cantidad a ser procesada. Para facilitar la mezcla de todos los ingredientes mueva con la cuchara hasta obtener una consistencia líquida. Posteriormente debe retirar del baño maría y lejos de la estufa agregar lentamente el "+componente['527'].n+". Por último agregamos el colorante, y con la cuchara lo incorporamos a la mezcla (si observa que el color no es lo suficientemente intenso, agregue más hasta obtener una coloración intensa).</p><h3>Envasado y conservación</h3><p> Vaciamos el contenido en el recipiente de plástico, y dejamos enfriar hasta que endurezca el producto. No olvide colocarle una etiqueta con el nombre del producto, color y fecha de fabricacion. Conserve bien cerrada, en un lugar seco.</p><h3>Caducidad</h3><p>Mientras se mantenga cerrada su crema después de cada aplicación, se conservará en buen estado por más de un año. Si se reseca por mal uso, repetiremos los pasos 2 y 3 del procedimiento de preparación.</p><h3>Dato interesante</h3><p>La cera es una sustancia sólida que segregan las abejas para formar las celdillas de los panales. Debido a su composición, la crema le proporciona a la piel de su calzado un excelente brillo, el cual es originado por la presencia de ácidos grasos entre otros, lo que permite que se lubrique.</p><h3>Beneficio</h3><p>Al elaborar usted mismo su producto obtendrá un ahorro del 50% con relación a un producto comercial. Además podrá elegir la fragancia de su preferencia (se recomienda añadir la fragancia de mirbana) para su calzado, con las variantes de aromas que existen en el mercado.</p>    <h3>Instrucciones de uso</h3><p>Antes de aplicar la crema, primero con un trapo retire el polvo de su calzado, posteriormente aplique uniformemente, y por último, con un trapo seco frote para proporcionar brillo (si observa que la crema no cubrió alguna raspadura en su calzado puede aplicar nuevamente).</p><h3>Recomendaciones generales</h3><p>Es recomendable trabajar en un lugar ventilado (abra las ventanas de su cocina para que entre aire), de lo contrario puede sufrir irritación en ojos y garganta. Cuando lave sus recipientes donde elaboró la crema para calzado, se recomienda que los deje remojar en agua caliente con jabón, y limpiar con toallitas de papel para remover los residuos de cera, si observa que el recipiente aún tiene una coloración negra, agregue agua con un poco de cloro y deje remojar el tiempo necesario. Si por algún motivo llegara a caerle la cera en su ropa, déjela secar, y rásquela con un cepillo. Los restos que queden, se recomienda que los planche, poniendo la tela entre dos papeles secantes (papel estraza). Si aun observa algún resto de la cera, tome un trapo mojado con bencina u otro solvente y frótelo sobre la superficie afectada. Después se recomienda lavar normalmente.</p>";

    document.getElementById('salida').innerHTML = resul,
    document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
ponerigual();

}
function crema_removedora_de_pintura_y_grasa_para_las_manos() {
    var UI = document.getElementById('entrada').value;
    if (isNaN(UI)) { return false; }
    componente['204'].v = p(29.94);
    componente['210'].v = p(29.94);
    componente['17'].v = p(9.98);
    componente['209'].v = p(29.94);
    componente['138'].v = p(0.1);
    componente['211'].v = p(0.1);

    tratamiento(UI);
    tratamiento_producto(UI);

    resul =
        tca + UI + tipoproducto + fo + tcc + olli +
        componente['204'].n + componente['204'].v + lili +
        componente['210'].n + componente['210'].v + lili +
        componente['17'].n + componente['17'].v + lili +
        componente['209'].n + componente['209'].v + lili +
        componente['138'].n + componente['138'].v + lili +
        componente['211'].n + componente['211'].v + liol;
quitarigual();
    var procedimiento = "<h3>PROCEDIMIENTO:</h3><h3>Descripcion</h3><p>Crema removedora de pintura (alquidálica, vinílica y acrílica) y grasa de mecánicos, para la piel, con aroma cítrico. No contiene thiner y está elaborada con ingredientes biodegradables. Ea amigable al contacto con la piel.</p><h3>Pasos a Seguir</h3><p>En un envase debe adicionar el "+componente['204'].n+" al "+componente['210'].n+" y se mezcla con agitación, posteriormente se agregan la "+componente['17'].n+", el "+componente['138'].n+" y el "+componente['211'].n+". Finalmente se incorpora el "+componente['209'].n+" y se agita hasta formar una crema homogénea libre de grumos.</p><h3>NOTAS IMPORTANTES</h3><p>El producto debe ser envasado en un recipiente metálico, ya que al contener el "+componente['204'].n+" puede deformar el envase. Debe tener una leyenda que diga que en caso de que se seque en el interior del envase, se agregue un poco de agua para restituirlo.</p><h3>INDICACIONES DE USO</h3><p>Ponga un poco de crema sobre la piel pintada y frote hasta que se remueva la pintura, posteriormente enjuague con agua y seque con una toalla. Evite el contacto con los ojos No se deje al alcance de los niños.</p>";

    document.getElementById('salida').innerHTML = resul,
    document.getElementById('procedimiento').innerHTML = procedimiento;
        //Linea agregada
popove();
ponerigual();

}








// function paramodal() {
//    var materiales = "<p>Son Bastante sencillos y casi que de uso comun, hay cosas que pueden ser sustituidos por otros instrumentos que hagan la misma funcion, como por ejemplo los instrumentos aforados pueden ser sustituidos por jarras o vasos de uso domestico que tengan marcas de medidas, si no tienen marcas usted puede hacer dichas marcas con marcadores permanentes o teipes, la idea de que esten marcadas las medidas es acelerar los procesos de produccion.</p>" + olli + "Tobos plasticos con 4 veces la capacidad de producto a preparar." + lili + "Paletas de madera, palos de escoba, cabos de madera." + lili + "PH metro o bandas medidoras de PH (es posible en algunos paises que el uso de las bandas resulten de pronto mas economicas), Tome en cuenta que si se decantan por utilizar Bandas Medidoras de PH las mismas son desechables y si usted va a mantener una produccion constante a la larga le sera mejor economicamente hablando el uso del dispositivo digital PH Metro." + lili + "Probetas de varias capacidades, tambien le puede servir jarras con medidas marcadas." + lili + "Delantal de cuero de chivo (Cuero de Ovino) o plastico resistente, para efectos de proteccion se pueden utilizar cualquiera de los 2 materiales, pero para efectos de durabilidad los delantales de cuero son mucho mas duraderos en el tiempo, los mismos los puedes encontrar en sitios donde confeccionen bolsos de cuero o zapatos, talabarterias, personas que fabriquen correas o productos de cuero, Tambien puede servir cuero de Vaca (Cuero de Bovino) pero el mismo tiende a ser muy pesado pero es mucho mas resistente y mas si se trabaja con acidos o alcalis fuertes." + lili + "Guantes aislantes de cuero o de goma. Evite manipular acidos o alcalis fuertes con guantes de latex." + lili + "Lentes de seguridad o careta de proteccion." + lili + "Tapa boca, filtros, deshumificadores, etc." + lili + "Pesos digitales o analogicos." + lili + "Embudos de varios tamaños." + lili + "Vestimenta adecuada, bragas, batas de laboratorio." + lili + "Se recomienda que en la instalacion donde se va a producir productos de limpieza se tenga una fuente de agua garantizada." + liol;
//document.getElementById('materiales').innerHTML = materiales;
//}
//paramodal();


function industrial(){
    var resultado = "";
    resultado += "";
    resultado += "<div class=\"panel panel-info\">";
    resultado += "<div class=\"panel-heading\"><strong>Instalaciones Industriales</strong><\/div>";
    resultado += "<div class=\"panel-body\">";
    resultado += imprimirxs;
    resultado += "<h2>Introduccion</h2><p>Es posible que al adquirir este manual digital las intenciones son las de activar la economia a un nivel domestico, fabricando productos que seran comercializados por usted mismo/a, pero que sucederia si el producto que usted comienza a fabricar gusta al consumidor y de alguna manera empieza a aumentar la demanda del producto, o mejor aun, es contactado por industrias de su localidad que deciden comprarle a usted productos de limpieza para dotar sus empresas y estas compran por cantidades ejemplo 50 litros de cada producto y dicha compra es cada quince dias, es decir ya por alli usted debe planificarse a producir un aprox de 200 litros de productos variados mensuales solo para 1 cliente, y si este tipo de clientes se incrementa tambien sucedera con la cantidad de producto terminado.</p><p>Es alli donde hay que pensar en ampliar los horizontes de nuestro emprendimiento y proceder al paso de ampliacion y tecnificacion de nuestro negocio con la finalidad de poder darnos abasto con la entrega de pedidos, es real que de la noche a la mañana no se logra tener una edificacion para dar trabajo a 500 personas pero al tener en mente un proyecto escalable este tema puede ser de gran utilidad para la toma de decisiones en el futuro proximo. </p>";
     resultado += "<div class='text-center'><img width='50%' src=\"media\/imagen\/general\/llenadoautomatico.jpg\"></div>";
    resultado += "<h2>Instalaciones Industriales</h2><p>Se entiende por Instalaciones Industriales al conjunto de medios necesarios para los procesos de fabricación, para permitir el uso del edificio, como medida de protección o seguridad, o para el almacenamiento de productos. La instalación industrial comprende: <ul><li>El edificio industrial. </li><li>Las máquinas o bienes de equipo. </li><li>Las máquinas o bienes de equipo.</li><li>Las instalaciones específicas.</li><li>Instalaciones de almacenamiento y distribución (gases, líquidos, sólidos).</li><li>Instalaciones de generación, distribución y transformación eléctrica.</li><li>Instalaciones de agua (proceso y potable).</li><li>Instalaciones de aire comprimido.</li><li>Instalaciones de protección contra incendios.</li><li>Instalaciones de saneamiento.</li><li>Instalaciones de frío industrial.</li><li>Instalaciones de climatización o de calefacción.</li></ul></p>";
    resultado += "<h2>Diseño de Plantas Industriales</h2><p>El diseño de plantas industriales es una labor de gestión que son dirigidas por especialistas con la finalidad de una buena distribución de espacio físico; en muchos casos el diseño está orientado para plantas nuevas y para la expansión de una existente.</p><p>Las plantas industriales son fábricas donde se elaboran diversos productos, su función es combinar el trabajo humano con las máquinas que se encuentran en sus instalaciones para transformar las materias primas y la energía.</p><p>Son importantes para contribuir a los objetivos empresariales, por lo que no debe realizarse superficialmente; se debe analizar todas las alternativas antes de seleccionar el lugar donde la fábrica opere en las mejores condiciones de costos, que tenga acceso a la infraestructura adecuada y un suministro.</p>";
    resultado += "<div class='text-center'><img width='50%' src=\"media\/imagen\/general\/lineadetrabajo.jpg\"></div>";
    resultado += "<h2>Objetivos del diseño de plantas industriales</h2> <ul> <li>Reducción del riesgo para la salud y aumento de la seguridad de los trabajadores.</li> <li>Incremento de la producción.</li> <li>Disminución en los retrasos de la producción.</li> <li>Acortamiento del tiempo de fabricación.</li> <li>Mayor facilidad de ajuste a los cambios de condiciones.</li> <li>Disminución del riesgo para el material o su calidad.</li> <li>El sitio más apropiado para establecer una planta industrial requiere ser estudiado minuciosamente, teniendo como bases principales lo relativo a la producción y a la colocación de los productos terminados.</li> </ul>";
    resultado += "<h2>Los factores para localizar una planta industrial:</h2> <ul> <li>La cercanía de las fuentes de materias primas y del mercado consumidor.</li> <li>La disponibilidad de mano de obra y la cercanía de los mercados laborales calificados para utilizar la tecnología del proyecto.</li> <li>Disposición de residuos, protección contra incendios, disminución del ruido</li> <li>Clima</li> <li>La disponibilidad y confiabilidad de los sistemas de apoyo (electricidad, agua potable, energía, comunicaciones, etc.).</li> <li>Condiciones sociales y culturales</li> <li>Consideraciones legales y políticas</li></ul> <p>La mano de obra será indispensable y se presenta tres dimensiones importantes en la localización de empresas: número de trabajadores, cualificación y coste. Toda planta industrial es la fusión perfecta entre el hombre y la máquina, trabajado como uno, donde la función principal del empleado es la obtención del mayor rendimiento de las máquinas.</p> <p>Otro factor a destacar para el diseño de una planta industrial es el conocimiento y la experiencia de los especialistas dedicados por completo a dicha labor, que efectúan de manera permanente una actualización e innovación continua en cada uno de los procesos que se tenga en la organización, contribuyendo esto a la mejora continua en los sistemas de producción y renovación en la cadena de suministros.</p> <p>Para generar lo menores costos y el mayor aprovechamiento de la empresa se debe tener un buen manejo de los sistemas de control logístico los cuales se basan en el uso adecuado de la información, este reduce tiempos y ayuda a detectar las anomalías presentadas en la diferentes instancias de la producción.</p>";
    resultado += "<h2>Arquitectura Industrial</h2> <p>Todos conocemos que la arquitectura es el componente artístico de las edificaciones. Torres, viviendas y todo tipo de inmuebles que sean diseñados por un buen equipo de arquitectos no solo poseen la cualidad de albergarnos, sino que su estética puede encantarnos. En pocas palabras, es la belleza de las construcciones. Sin embargo, pocos sabemos específicamente en qué consiste la arquitectura industrial.  Pues tiene que ver con un asunto histórico o de evolución. Y es que puede llamársele una de las etapas de la evolución de la arquitectura, que tiene que ver con los cambios económicos, políticos y culturales de las sociedades a lo largo de la historia. Así, la arquitectura industrial tuvo que ver con la revolución industrial.</p> <p>Como se sabe, la revolución industrial sucedió alrededor de la segunda mitad del siglo XVIII, y, con ella, la humanidad adquirió nuevas necesidades, especialmente lo que vino a denominarse la industria, como fábricas (con maquinarias y trabajadores), pero también puentes, hospitales… todo lo que una ciudad moderna necesitó luego del avance tecnológico que supuso esta revolución.</p> <p>En esa línea, la arquitectura industrial se dedicó al diseño de las edificaciones que las nuevas sociedades, marcadas por los cambios que trajo esa revolución tecnológica, requerían. Hoy en día, se puede decir que si la arquitectura en general diseña estéticamente las construcciones, la arquitectura industrial diseña las construcciones destinadas a albergar maquinarias de las empresas, fábricas, o cuyo empleo del espacio sea netamente utilitario más que artístico.</p> <p>Por ello, la arquitectura industrial sigue ciertos parámetros diferenciados para el diseño de esas edificaciones, como la funcionalidad, el ahorro en la compra de los materiales y el abandono parcial del lujo. Claro, su objetivo no es tan estético, sino que busca cumplir con los objetivos económicos de la empresa o cliente que requiere esa construcción.</p> <p>En cuanto a los materiales más utilizados en esta rama arquitectónica, luego de la revolución, fueron el hierro fundido, acero laminado, hormigón armado o el vidrio. A raíz de estas preferencias por los materiales (más accesibles por su producción en serie, no como el bronce que se usaba en la antigüedad) es que las construcciones se dieron a ritmos más acelerados, a la velocidad que imponía la industrialización.</p> <p>Así, la arquitectura industrial mantiene un equilibrio entre el cumplimiento de los objetivos empresariales y la estética. Prueba de aquello son los rascacielos como el Burj Khalifa (Dubai), el Empire State (EE.UU.) o el edificio Chrysler (Nueva York); el Puente de Coalbrookdale (el primer puente en arco de hierro fundido, en Inglaterra); bibliotecas como la de Santa Genoveva (París); La Torre Eiffel (Francia); invernaderos como el Jardines de San José (Málaga) o el Jardín Botánico de Kew (Londres).</p> <p>Lo cierto es que mucho le debemos a la arquitectura industrial, una rama fantástica que atiende hasta hoy las urgencias del nuevo mundo industrializado, otorgando a la humanidad una combinación ideal: diseño de espacios útiles y bellos.</p>";
    resultado += "<div class='text-center'><img width='50%' src=\"media\/imagen\/general\/instalacionesindustriales.jpg\"></div>";
    resultado += "<p></p>";
    resultado += "<p></p>";
    resultado += "<p></p>";
    resultado += "<p></p>";
    resultado += "<p></p>";
    resultado += " <\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";

    document.getElementById('contenido').innerHTML = resultado;
}
function ph(){
    var resultado = "";
    resultado += "";
    resultado += "<div class=\"panel panel-info\">";
    resultado += "<div class=\"panel-heading\"><strong>Leccion sobre PH</strong><\/div>";
    resultado += "<div class=\"panel-body\">";
    resultado += imprimirxs;
    resultado += "<p class='bg-primary'>Puede visitar la siguiente direccion donde se amplian detalles sobre <a href='http://www.ehu.eus/biomoleculas/ph/neutra.htm' target='_blank' type='button' class='btn btn-warning'>NEUTRALIZACION DE ACIDOS Y BASES</a> </p><p>Todos hemos oído hablar del PH en todos los productos químicos, y más los de limpieza, pero si nuestra especialidad no es el area quimica es posible que no sea muy familiar lo qué significa el PH en un producto químico y para qué sirve.</p><p>PH significa Potencial de Hidrógeno, e indica la acidez o alcalinidad de un producto, pero ¿ésto en qué se traduce?</p><p> Normalmente la escala del PH va desde 0 a 14, de tal forma que podemos decir que un producto es ácido si su PH va de 0 a 7 aproximadamente, y un producto es alcalino si su PH va de 7 en adelante.Ejemplos de sustancias ácidas pueden ser el limón o vinagre y de sustancias alcalinas la lejía o el amoniaco.</p><p>  Se ha determinado que la piel humana ronda el PH 5.5 con lo que un producto con menor PH podría producir irritaciones.Un PH ácido podría quemarnos la piel y un PH alcalino podría incluso disolverla.</p> <p>Cada producto y cada marca tienen unos PH determinados por la composición química, pero como norma general indicamos lo habitual según el tipo de producto o superficie a tratar:</p><ul><li><strong>Productos neutros (PH de 6 a 8).</strong> Son productos que se pueden utilizar en superficies o suelos cristalizados y con brillo, por ejemplo el marmol, ya que no alteran las propiedades del brillo.Pueden ser utilizados para uso personal ya que no afectan a la piel.</li><p>    Ejemplos de productos neutros: desinfectantes pino o limón, desinfectantes para mármol, Jabón para manos.</p><li><strong> Productos alcalinos (PH 9 o mayor). </strong> Son productos que poseen propiedades desinfectantes y limpiadoras, especialmente si la suciedad contiene pigmentos, proteínas o grasas.Si el PH es muy alto suele utilizarse como desatascador.</li><p>    Ejemplos de productos alcalinos: Desengrasantes, Lavavajillas para máquinas industriales, o desinfectantes amoniacal.</p><li> <strong>Productos ácidos (PH 5 o menor). </strong> Son productos con propiedades típicamente desincrustantes, ideales para restos calcáreos, óxidos, etc.Pero cuidado: se deben usar en superficies no delicadas.</li><p>    Ejemplos de productos ácidos: Abrillantadores, lavaplatos, Limpiadores cítricos entre otros.</p>";

    resultado += "<h3>PH en Detergentes</h3>";
    resultado += "<p>Uno de los productos de limpieza más comúnmente utilizado, es el detergente, el cual es usado de 3 a 4 veces al día en una casa común.</p><p>    Pero primero se debe definir que es un detergente, este se define como: \“Un detergente es una sustancia que se utiliza para limpiar ya que dispone de propiedades que le permiten quitar la suciedad sin afectar el material sometido al proceso de limpieza\”</p><p>    El punto de vista que se abarcara en esta entrada es el contenido de PH que posee el detergente, como es que este afecta su acidez y como lo ayuda a cumplir su función principal, que es limpiar y remover suciedad y cuánto debe de ser la cantidad de PH aproximado para que no sea una sustancia dañina.</p><p>    El PH nos indica el grado de alcalinices o acidez del detergente, dependiendo de este nivel obtendremos en los materiales en los cuales se aplique unos efectos u otros.</p>";
    resultado += "<div class='text-center'><img width='50%' src=\"media\/imagen\/general\/ph.jpg\"></div>";
    resultado += "<p>El PH de un detergente normal debe de ser de 10.5 que en comparación con otros elementos es muy elevado, es por eso que provoca irritación e incomodidad.    El agua pura tiene un valor pH de aprox. 7 y se la denomina neutra. Las soluciones ácidas tienen un valor pH más bajo. En los productos alcalinos, el valor pH es más alto.Los productos de limpieza ácidos son adecuados para eliminar depósitos de calcio y manchas de herrumbre(sustancia que se forma sobre el hierro metálico expuesto a la humedad, es una sustancia color marrón), y los alcalinos son adecuados para eliminar suciedades de contenido graso y aceitoso.</p>";
    resultado += "<div class='text-center'><img width='70%' src=\"media\/imagen\/general\/ph2.jpg\"></div>";
    resultado += "<p>Las propiedades ácido–básicas de los compuestos orgánicos son importantes para su función en los seres vivos; desde su distribución hasta su destino metabólico son determinados por el carácter ácido o básico además, la acidez del medio en que se encuentran, también tiene efecto sobre ellos. Por tal motivo, para comprender cabalmente la Bioquímica, es necesario un conocimiento sólido de los fundamentos del comportamiento de los ácidos y las bases. El término ácido proviene del latín acidus que significa \“agrio\”, y se refiere al sabor característico de estos compuestos; además del sabor, los ácidos en general son substancias que provocan vire del tornasol azul a rojo, reaccionan con los metales liberando Hidrógeno, al tacto tiene sensación acuosa, y pierden estas propiedades cuando reaccionan con bases. Las bases también se denominan álcalis, nombre que proviene del griego alqili y que significa \“ceniza\”, porque estas eran la fuente de donde se obtenían los álcalis. Sus propiedades características incluyen un sabor amargo, viran el color del tornasol de rojo a azul, al tacto son resbalosas o jabonosas, y reaccionan con los metales formando hidróxidos, frecuentemente insolubles.</p><h3>La Escala de pH</h3><p>La escala de pH se obtuvo a partir del estudio del comportamiento ácido-base del agua. El agua se comporta como un no-electrolito, a pesar de que tiene la capacidad para actuar como ácido o base; cuando está pura, sus moléculas se disocian muy poco.</p><div class='text-center'>H<sub>2</sub>O <span class=\"glyphicon glyphicon-resize-horizontal\" aria-hidden=\"true\"></span> H<sup>+</sup> + OH<sup>-</sup></div>";
    resultado += "<h3>Cálculo de pH</h3><p>Para calcular el pH, es necesario conocer la concentración de protones de la solución. En el caso de las soluciones de ácido fuerte, cada molécula de ácido cede todos los protones ácidos que posee por lo tanto, la concentración molar de H<sup>+</sup> es igual a la Molaridad del ácido multiplicada por su valencia, lo que es igual a la Normalidad del ácido. Entonces, para calcular el pH de una solución de ácido fuerte, simplemente se sustituye [H<sup>+</sup>] con la Normalidad del ácido en la fórmula de definición del pH:</p><div class='text-center'>pH = - log N<sub>ÁCIDO</sub></div><p>Para calcular el pH de soluciones de bases fuertes, primero se calcula el pOH a partir de la Normalidad de la base, y después se convierte en pH, en la forma antes explicada.</p>";
 resultado += "<h3>¿Para qué sirve el PHmetro?</h3><p>Este instrumento tiene un sensor el cual es utilizado para medir el PH de una disolución. Quiere decir que junto con los electrodos, el voltímetro será sumergido en la sustancia haciendo que genere una corriente eléctrica, es así que la concentración de iones de hidrógenos presenta la solución en la corriente eléctrica. Esto se da por medio de la membrana de vidrio que tiene el pH metro la cual obtiene la sensibilidad y selectividad de las dos soluciones de concentración.</p>";
resultado += "<div class='text-center'><img width='50%' src=\"media\/imagen\/general\/phmetro.jpeg\"></div>";
 resultado += "<h4>Fundamento del phmetro</h4><p>Los phmetro miden el voltaje entre dos electrodos y muestran el resultado convertido en el valor de pH correspondiente. Comprende un amplificador electrónico simple y un par de electrodos, o alternativamente un electrodo combinado, y una pantalla calibrada en unidades de pH.</p><p>Usualmente tiene un electrodo de vidrio y un electrodo de referencia, o un electrodo de combinación. Los electrodos, o sondas, se insertan en la solución a ensayar y su diseño es la parte clave ya que son estructuras parecidas a varillas generalmente hechas de vidrio, con un bulbo que contiene el sensor en la parte inferior.</p><p>El electrodo de vidrio para medir el pH tiene un bulbo de vidrio específicamente diseñado para ser selectivo a la concentración de iones de hidrógeno. En la inmersión en la solución que se probará, los iones de hidrógeno en el intercambio de la solución de prueba para otros iones positivamente cargados en el bulbo de cristal, creando un potencial electroquímico a través del bulbo.</p><p>El amplificador electrónico detecta la diferencia de potencial eléctrico entre los dos electrodos generados en la medición y convierte la diferencia de potencial en unidades de pH. La magnitud del potencial electroquímico a través de la bombilla de vidrio está linealmente relacionada con el pH según la ecuación de Nernst. El electrodo de referencia es insensible al pH de la solución que se compone de un conductor metálico que se conecta a la pantalla. Este conductor está inmerso en una solución electrolítica, típicamente cloruro de potasio, que entra en contacto con la solución de ensayo a través de una membrana cerámica porosa.</p>";
 resultado += "<h4>Partes del Phmetro</h4><ol><li>Electrodo de pH.</li><li>Elemento de compensación de temperatura.</li><li>Medidor de pH o controlador.</li></ol>";
 resultado += "<h4>Tipos de phmetro</h4><p>Los phmetros van desde dispositivos simples y baratos como bolígrafos a complejos y costosos instrumentos de laboratorio con interfaces de ordenador y varias entradas para indicadores de temperatura y mediciones que deben introducirse para ajustarse a la variación en pH causada por la temperatura.</p><p>La salida puede ser digital o analógica, y los dispositivos pueden ser alimentados por batería o depender de la energía electrica. Algunas versiones utilizan telemetría para conectar los electrodos al dispositivo de visualización del voltímetro. Los phmetros y las puntas de prueba están disponibles para usos especiales, tales como ambientes complejos y microambientes biológicos.</p><p>También hay sensores de pH holográficos, que permiten la medición del pH colorimétrico, haciendo uso de la variedad de indicadores de pH que están disponibles. Además, hay phmetros disponibles basados en los electrodos de estado sólido.</p>";
 resultado += "<div class='text-center'><img width='50%' src=\"media\/imagen\/general\/phmetroanalogico.jpg\"></div>";
 resultado += "<h4>Calibración del phmetro</h4><p>El medidor de pH está calibrado con soluciones de pH conocido antes de cada uso, para asegurar la exactitud de la medición. Para medir el pH de una solución, los electrodos se utilizan con puntas de prueba que se sumergen en las soluciones de la prueba y se mantienen los iones de hidrógeno en la solución de la prueba para equilibrar con los iones en la superficie del bulbo en el electrodo de cristal. Este equilibrado proporciona una medición estable del pH.</p> <p>El Instituto alemán de estandarización publica da estándar para la medición del pH utilizando medidores de pH que requieren que el medidor de pH esté calibrado antes de cada medición.</p> <p>La calibración es necesaria porque el electrodo de vidrio no da potenciales electrostáticos reproducibles durante largos períodos de tiempo y se realiza con al menos dos soluciones estándar que abarcan el rango de valores de pH a medir. El medidor de pH tiene un control de calibración para ajustar la lectura del medidor igual al valor del primer estándar y un segundo control que se utiliza para ajustar la lectura del medidor al valor del segundo estandar. Un tercer control permite ajustar la temperatura.</p> <p>Las mediciones más precisas a veces requieren calibración a tres valores de pH diferentes. Algunos medidores de pH proporcionan una corrección de coeficiente de temperatura incorporada, con termopares de temperatura en las sondas de electrodos. El proceso de calibración correlaciona el voltaje producido por la sonda (aproximadamente 0,06 voltios por unidad de pH) con la escala de pH.</p> <p>Después de cada medición las sondas se enjuagan con agua destilada para eliminar cualquier rastro de la solución y absorber cualquier agua restante con un pañuelo ya que podría diluir la muestra y así alterar la lectura, y la punta se sumerge en una solución para su almacenaje.</p>";
    resultado += " <\/div>";
    resultado += "<\/div>";
    resultado += "<\/div>";

    document.getElementById('contenido').innerHTML = resultado;
}


$(document).ready(function () {
    $("form").keypress(function (e) {
        if (e.which == 13) {
            return false;
        }
    });
});
