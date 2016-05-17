<script>
    <!--
    var referenz, meinEssen, name, element, box, error, locname, locpage;

    var locessen = [];
    var essenDropdown = [];

    function loc_anlegen() {
        locname = document.getElementById("locname").value;
        locpage = document.getElementById("locpage").value;
        box     = document.getElementById("gewaehlte_essen");
        for (var i = 0; i < box.options.length; i++) {
            locessen[i] = box.options[i].text;
        }

        $.ajax({
            type    : "POST",
            url     : "procedures.php",
            data    : {callFunction: 'insertLocation', p1: locname, p2: locpage, p3: locessen},
            dataType: 'text',
            success : function (data) {
				window.location.reload();
            }
        });
    }

    function essen_laden() {
        box = document.getElementById("verfuegbare_essen");

        $.ajax({
            type    : "POST",
            url     : "procedures.php",
            data    : {callFunction: 'reloadEssen'},
            dataType: 'text',
            success : function (data) {
                essenDropdown = JSON.parse(data);

                for (var i = 0; i < essenDropdown.length; i++) {
                    element = document.createElement("option");
                    element.appendChild(document.createTextNode(essenDropdown[i]['name']));
                    box.appendChild(element);
                }
            }
        });

    }

    function essen_zuweisen() {
        referenz  = document.newloc.verfuegbare_essen;
        meinEssen = referenz.value;

        box   = document.getElementById("gewaehlte_essen");
        error = false;

        for (var i = 0; i < box.options.length; i++) {
            if (box.options[i].text == meinEssen) {
                error = true;
            }
        }
        if(!error) {
            if (meinEssen && box.options.length < 15) {
                element = document.createElement("option");
                element.appendChild(document.createTextNode(meinEssen));
                box.appendChild(element);
                $('#gewählt').css('display', 'table-row');
            }
            else {
                alert("Höchstens 15 Essen pro Location möglich");
            }
        }
        return false;
    }

    function essen_entfernen() {
        box = document.getElementById("gewaehlte_essen");
        box.remove(box.selectedIndex);
        var gewählteEssen = $('#gewaehlte_essen option').val();
        if(gewählteEssen == null) $('#gewählt').css('display', 'none');
    }

    function locations_abfragen() {

        $.ajax({
            type    : "POST",
            url     : "procedures.php",
            data    : {callFunction: 'insertEssen'},
            dataType: 'text',
            success : function (data) {
                window.location.reload();
            }
        });
        // window.location.reload();
    }

    function getLocations() {
        $.ajax({
            type    : "POST",
            url     : "procedures.php",
            data    : {callFunction: 'getLocations'},
            dataType: 'text',
            success : function (data) {
                var locations= JSON.parse(data);
                $('#demoThree').html("");
                for(i=0; i<locations.length; i++) {
                    var location = JSON.parse(locations[i]);
                    var essenString="";
                    var essen = location['essen'];
                    if(essen) {
                        essenString = "Essen: "
                        essen.forEach(function (s, i, a) {
                            if(essen[i+1]) essenString = essenString.concat(s + ", ");
                            else essenString = essenString.concat(s);
                        });
                    }
                    var link = location['link'];
                    var linkString = "";
                    if(link) linkString = "Link: <a href=\'"+location['link']+"\' >"+location['link']+"</a><br>";
                    $('#demoThree').append("<li><a href=\"#locations\" data-trigger=\"focus\" data-toggle=\"popover\" title=\""+location['name']+"\" data-content=\""+ linkString + essenString + "\" data-html=\"true\">" + location['name'] +"</a></li>");
                }
                $('[data-toggle="popover"]').popover();
                $(function(){
                    $('#demoThree').listnav({
                        initLetter: 'all',
                        includeNums: true,
                        allText: 'Alle',
                        noMatchText: 'Keine Einträge für diesen Buchstaben vorhanden.'
                    });
                });
            }
        });
    }

    function removeBilder() {
        $('#navbarPages').html("<li><a href=\"#locations\"> <h3>Locationsverwaltung</h3></a></li><li><a href=\"#essen\"><h3>Essensverwaltung</h3></a></li>");
    }

    -->
</script>

<script> removeBilder();</script>
<div class="col-md-5">
    <div class="col-md-12">

    <h2>Location hinzufügen</h2>
    <br>
    <form id="newloc" name="newloc" action="" method="post" onsubmit="loc_anlegen(); return false;">
        <table class="usertable">
            <tbody>
            <tr>
                <td><label for="locname">Name der Location:</label></td>
                <td><input type="text" class="fancyform" id="locname" maxlength="30" value="" required></td>
            </tr>
            <tr>
                <td><label for="locpage">Homepage: </label></td>
                <td><input type="text" class="fancyform" id="locpage" maxlength="100" value=""></td>
            </tr>
            <tr>
                <td><label for="verfuegbare_essen">Essensmöglichkeiten:</label></td>
                <td><select id="verfuegbare_essen" class="fancyform" name="verfuegbare_essen"></select></td>
                <td style="text-align:right"><button type="button" class="btn btn-default" onclick="essen_zuweisen();" title="Maximal 15 Essen zur Location hinzufügen">Hinzufügen</button></td>
            </tr>
            <tr id="gewählt" style="display:none">
                <td><label for="verfuegbare_essen">Gewählt:</label></td>
                <td><select id="gewaehlte_essen" class="fancyform" name="gewaehlte_essen" size="4"></select></td>
                <td style="text-align:right"><button type="button" class="btn btn-danger" onclick="essen_entfernen();">Entfernen</button></td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align:left"><button type="submit" class="btn btn-primary">Location speichern</button></td>
            </tr>
            </tbody>
        </table>
    </form>
    </div>
</div>
<!-- Alphabet -->
<div class="col-md-7">
    <br>
    <div id="tabpage_3" class="col-md-12">

        <div class="listWrapper">

            <ul id="demoThree" class="demo">
            </ul>
            <script> getLocations(); </script>

        </div>
     </div>
</div>

<script src="js/jquery-listnav.js"></script>
<script src="js/vendor.js"></script>

<script>essen_laden();</script>
