<?php
require_once __DIR__ . "/../required.php";
?>
<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sample Publication | 2017-12-02</title>
<style nonce="<?php echo $SECURE_NONCE; ?>">.tile-bin {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
        padding: 5px;
        margin: 10mm 10mm 10mm 10mm;
    }
    .pub-content {
        border: 1px solid grey;
        margin: 0px auto;
        box-shadow: 5px 5px 15px -3px rgba(0,0,0,0.75);
        margin-bottom: 20px;
        overflow: auto;
    }
    .page-safe-line div {
        display: none;
        background-color: grey;
        position: relative;
    }
    .page-safe-line .bottom {
        height: 1px;
        left: 0;
        right: 0;
    }
    .tile {
        margin: 5px;
    }
    .tile-html {
        font-family: sans-serif;
        min-height: 10px;
    }
    .tile-html img {
        max-width: 100%;
    }
    @media print {
        @page {
            margin: 10mm 10mm 10mm 10mm;
        }
        .tile-bin {
            margin: 0px;
            overflow: visible;
        }
        .pub-content {
            z-index: 999999;
            border: 0px;
            box-shadow: none;
            overflow: visible;
        }
        .btn-group, .footer {
            display: none;
        }
        .page-safe-line {
            display: none;
        }
    }</style>
<style nonce="<?php echo $SECURE_NONCE; ?>">
    .pub-content {
        <?php
        $pubvars = json_decode(file_get_contents(__DIR__ . "/pub_styles/" . $_GET["pub"] . "/vars.json"), TRUE);
        foreach ($pubvars as $name => $val) {
            echo "--$name: $val;\n";
        }
        ?>
    }

    .pub-content {
        <?php include __DIR__ . "/pub_styles/" . $_GET["pub"] . "/pub.css"; ?>
    }

    <?php include __DIR__ . "/pub_styles/" . $_GET["pub"] . "/extra.css"; ?>

    .pub-content {
        background-image: url('data:image/png;base64,<?php echo base64_encode(file_get_contents(__DIR__ . "/pub_styles/" . $_GET["pub"] . "/background.png")) ?>');
    }
    .pub-content {
        max-width: 8.5in;
        height: 11in;
    }
    @media (max-width: 900px) {
        .pub-content {
            height: auto;
            min-height: 11in;
        }
    }
    .page-safe-line .bottom {
        top: calc(11in - 5mm);
    }
</style>
<style nonce="<?php echo $SECURE_NONCE; ?>">
<?php include __DIR__ . "/pub_styles/" . $_GET["pub"] . "/extra.css"; ?>
</style>
<style nonce="<?php echo $SECURE_NONCE; ?>" media="all">
    .tile-style-1 {
        <?php include __DIR__ . "/tile_styles/" . $_GET["tile"] . "/tile.css"; ?>
    }
    #tile-29 {
        order: 1;
        width: 100%;
        flex-basis: 100%;
        flex: 0 0 calc(100% - 10px);
    }
    #tile-31 {
        order: 1;
        width: 50%;
        flex-basis: 50%;
        flex: 0 0 calc(50% - 10px);
    }
    #tile-30 {
        order: 2;
        width: 50%;
        flex-basis: 50%;
        flex: 0 0 calc(50% - 10px);
    }
    #tile-32 {
        order: 3;
        width: 100%;
        flex-basis: 100%;
        flex: 0 0 calc(100% - 10px);
    }
    #tile-33 {
        order: 1;
        width: 75%;
        flex-basis: 75%;
        flex: 0 0 calc(75% - 10px);
    }
    #tile-34 {
        order: 1;
        width: 25%;
        flex-basis: 25%;
        flex: 0 0 calc(25% - 10px);
    }
    #tile-35 {
        order: 1;
        width: 50%;
        flex-basis: 50%;
        flex: 0 0 calc(50% - 10px);
    }
</style>
<div class="pub-content">
    <div class="page-safe-line">
        <div class="bottom"></div>
    </div>
    <div class="tile-bin">
        <div class="tile" id="tile-29" data-tileid="29" data-page="1" data-styleid="1" data-width="4" data-order="1">
            <div id="tile-29-content" class="tile-style-1">
                <div class="tile-html"><h1>Test Publication Header 1</h1><h2>Header 2</h2><h3>Header 3<br></h3></div>
            </div>
        </div>
        <div class="tile" id="tile-31" data-tileid="31" data-page="1" data-styleid="1" data-width="2" data-order="1">
            <div id="tile-31-content" class="tile-style-1">
                <div class="tile-html"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praeclare hoc 
                        quidem. Quis istum dolorem timet? Bonum integritas corporis: misera 
                        debilitas. Estne, quaeso, inquam, sitienti in bibendo voluptas? Memini 
                        vero, inquam; Prioris generis est docilitas, memoria; <br></p></div>
            </div>
        </div>
        <div class="tile" id="tile-30" data-tileid="30" data-page="1" data-styleid="1" data-width="2" data-order="2">
            <div id="tile-30-content" class="tile-style-1">
                <div class="tile-html"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praeclare hoc 
                        quidem. Quis istum dolorem timet? Bonum integritas corporis: misera 
                        debilitas. Estne, quaeso, inquam, sitienti in bibendo voluptas? Memini 
                        vero, inquam; Prioris generis est docilitas, memoria; <br></p></div>
            </div>
        </div>
        <div class="tile" id="tile-32" data-tileid="32" data-page="1" data-styleid="1" data-width="4" data-order="3">
            <div id="tile-32-content" class="tile-style-1">
                <div class="tile-html"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <b>Nunc agendum est subtilius.</b> Aut, Pylades cum sis, dices te esse Orestem, ut moriare pro amico? Causa autem fuit huc veniendi ut quosdam hinc libros promerem. Hoc loco tenere se Triarius non potuit. <i>De illis, cum volemus.</i> Duo Reges: constructio interrete. </p><p>
                        <br></p><pre>Nam cum in Graeco sermone haec ipsa quondam rerum nomina
novarum * * non videbantur, quae nunc consuetudo diuturna
trivit;
                    </pre><p>
                    </p><dl><dt><dfn>Nos commodius agimus.</dfn></dt><dd>Sed plane dicit quod intellegit</dd></dl>
                    <br><p></p><ul><li>Nobis Heracleotes ille Dionysius flagitiose descivisse videtur a Stoicis propter oculorum dolorem.</li><li>Quasi vero aut concedatur in omnibus stultis aeque magna esse vitia, et eadem inbecillitate et inconstantia<br></li></ul><blockquote cite="http://loripsum.net">
                        Nam haec ipsa mihi erunt<br></blockquote><ol><li>Sed in rebus apertissimis nimium longi sumus.</li><li>Rapior illuc, revocat autem Antiochus, nec est praeterea, quem audiamus.</li></ol><p><mark>Eaedem res maneant alio modo.</mark> Summum ením bonum exposuit vacuitatem doloris; Quod ea non occurrentia fingunt, vincunt Aristonem; Prioris generis est docilitas, memoria; Beatus autem esse in maximarum rerum timore nemo potest. Ut scias me intellegere, primum idem esse dico voluptatem, quod ille don. Sed ad bona praeterita redeamus. <i>Is es profecto tu.</i> Quod quidem iam fit etiam in Academia </p></div>
            </div>
        </div>
    </div>
</div>
<div class="pub-content">
    <div class="page-safe-line">
        <div class="bottom"></div>
    </div>
    <div class="tile-bin">
        <div class="tile" id="tile-33" data-tileid="33" data-page="2" data-styleid="1" data-width="3" data-order="1">
            <div id="tile-33-content" class="tile-style-1">
                <div class="tile-html"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ita graviter et severe voluptatem secrevit a bono. Bonum negas esse divitias, praeposìtum esse dicis? Facillimum id quidem est, inquam. Qui potest igitur habitare in beata vita summi mali metus? </p><p>
                        <br></p><ul><li>Nam aliquando posse recte fieri dicunt nulla expectata nec quaesita voluptate.</li><li>Inquit, dasne adolescenti veniam?</li><li>Duo Reges: constructio interrete.</li><li>Idem etiam dolorem saepe perpetiuntur, ne, si id non faciant, incidant in maiorem.</li></ul><p>
                        <br></p><blockquote cite="http://loripsum.net">
                        Fatebuntur Stoici haec omnia dicta esse praeclare, neque eam causam Zenoni desciscendi fuisse
                    </blockquote><p>
                        <br></p></div>
            </div>
        </div>
        <div class="tile" id="tile-34" data-tileid="34" data-page="2" data-styleid="1" data-width="1" data-order="1">
            <div id="tile-34-content" class="tile-style-1">
                <div class="tile-html"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quid est, quod ab ea absolvi et perfici debeat? At ego quem huic anteponam non audeo dicere; Duo Reges: constructio interrete. Illa tamen simplicia, vestra versuta. Quid dubitas igitur mutare principia naturae? Summus dolor plures dies manere non potest? Videmus igitur ut conquiescere ne infantes quidem possint. Sed ne, dum huic obsequor, vobis molestus sim </p></div>
            </div>
        </div>
        <div class="tile" id="tile-35" data-tileid="35" data-page="2" data-styleid="1" data-width="2" data-order="1">
            <div id="tile-35-content" class="tile-style-1">
                <div class="tile-html"><p><img style="width: 200px;" src="https://picsum.photos/200/300"><br></p></div>
            </div>
        </div>
    </div>
</div>
