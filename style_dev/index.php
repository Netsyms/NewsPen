<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Theme Editor</title>
<link rel="stylesheet" href="https://cdn.netsyms.com/bootstrap/4.0.0/bootstrap.materia.min.css" />
<style>
    #preview {
        width: 50vw;
        height: 99vh;
        position: absolute;
        left: 50vw;
        border: none;
    }

    #editor {
        width: 50vw;
        height: 99vh;
        position: absolute;
        left: 0vw;
        overflow-y: scroll;
    }
</style>
<div id="editor">
    <form action="doc.php" method="POST" target="preview" id="editform">
        <label>CSS rules to apply to each page:</label>
        <textarea name="pub" id="pub" placeholder="Publication CSS rules" class="form-control" rows="6">
background-position: right bottom;
background-repeat: no-repeat;</textarea>
        <label>Extra CSS:</label>
        <textarea name="extra" id="extra" placeholder="Extra CSS" class="form-control" rows="8">
.tile-html h1,h2,h3,h4,h5,h6 {
    color: var(--text);
}

.tile-html blockquote {
    background-color: var(--light-alpha);
    border-left: 3px solid var(--secondary);
    padding: 5px 10px;
    display: inline-block;
}

.tile-html pre {
    background-color: var(--light-alpha);
    border: 1px solid var(--secondary);
    border-left: 3px solid var(--secondary);
    padding: 5px 10px;
    display: inline-block;
}</textarea>
        <label>CSS variables (as JSON):</label>
        <textarea name="vars" id="vars" placeholder="JSON CSS variables" class="form-control" rows="4">
{
    "primary": "#973b01",
    "secondary": "#bc525b",
    "text": "#2f0701",
    "light": "#ffe46f",
    "light-alpha": "rgba(255,228,111,.25)",
    "medium": "#ff9e01"
}</textarea>
        <input type="text" class="form-control" name="bgurl" placeholder="Background image URL" />
        <input type="submit" class="btn btn-block btn-primary" value="Reload Preview" />
    </form>
</div>
<iframe id="preview" name="preview" src="doc.php">

</iframe>
<script>
    window.onload = function () {
        document.forms['editform'].submit();
    }

    var texts = document.getElementsByTagName('textarea')
    for (var i = 0; i < texts.length; i++) {
        texts[i].onblur = function () {
            document.forms['editform'].submit();
        }
    }
</script>