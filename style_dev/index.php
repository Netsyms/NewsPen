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
background-color: var(--background);</textarea>
        <label>Extra CSS rules for tile content:</label>
        <textarea name="extra" id="extra" placeholder="Extra CSS" class="form-control" rows="8">
.tile-html h1,h2,h3,h4,h5,h6 {
    color: var(--headings);
}</textarea>
        <label>Color variables:</label>
        <textarea name="vars" id="vars" placeholder="Color variables" class="form-control" rows="4">
{
    "primary": "#ff0000",
    "secondary": "#00ff00",
    "medium": "#0000ff",
    "headings": "#ff00ff",
    "background": "#ffffff",
    "text": "#000000"
}</textarea>
        <label>Metadata (used for generating theme picker UI):</label>
        <textarea name="meta" id="meta" placeholder="JSON metadata" class="form-control" rows="4">
{
    "name": "My Theme",
    "colors": ["#ff0000", "#00ff00", "#0000ff"]
}
</textarea>
        <input type="text" class="form-control" name="bgurl" placeholder="Background image URL" />
        <input type="submit" class="btn btn-block btn-primary" value="Reload Preview" />
    </form>
</div>
<iframe id="preview" name="preview" src="about:blank">

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