<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>

    <div id="wrapper">

        <div align="center">
            <h1>Doktor Glas</h1>

            <p>av Hjalmar Söderberg
        </div>
        <hr>
        <h2>Förord till den elektroniska utgåvan</h2>

        <p>Utkom första gången 1905. Elektronisk utgåva av Lars Aronsson
            i augusti 1995.

        <p>Läs även Oscar Levertins <a href="/olrecens/drglas.html">recension</a> av boken.

        <p>Romanen Doktor Glas utkom 1905, året innan ecklesiastikminister
            Fridtjuv Berg utfärdade sin "stavningsukas", varför originalutgåvan
            torde ha varit gammalstavad. Den utgåva av Doktor Glas, som använts
            till förlaga för den elektroniska utgåvan, använder modern stavning
            utom i ordet groft och i sammansättningar med lifs- (-tid, -uppgift),
            och torde därför varit underkastad språklig bearbetning. Denna
            elektroniska utgåva använder modern stavning fullt ut. Möjligen är
            det samma språkliga bearbetning som resulterat i att de sista datumen
            i augusti kommer i fel ordning (25-28-27) i förlagan. Ska det kanske
            vara 25-26-27 eller 25-28-29? Tills vidare följer den elektroniska
            utgåvan sin förlaga.

        <p>Manuskriptet till Doktor Glas kan läsas på <a href="http://litteraturbanken.se/#!/forfattare/SoderbergH/titlar/DoktorGlasMS/faksimil" target=_blank>Litteraturbanken</a>.
    </div>

    <?php
    function inspect($variable)
    {
        echo "<pre>";
        var_dump($variable);
        echo "</pre>";
    }
    function loadJSON($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
        $data = json_decode(file_get_contents($filename), true);
        return $data;
    }
    // Sparar data
    function saveJSON($filename, $data)
    {
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
        return true;
    }
    function saveChapter($key, $scroll)
    {
        $chapter = loadJSON("senasteKapitel.json");
        $chapter["lastChapter"] = $key;
        $chapter["scroll"] = $scroll;
        saveJSON("senasteKapitel.json", $chapter);
    }

    if (isset($_GET["chapter"])) {
        saveChapter($_GET["chapter"], $_GET["scroll"]);
    }
    $chapter = loadJSON("senasteKapitel.json");
    $lastChapterRead = $chapter["lastChapter"];

    $files = scandir("text");
    echo "<form action='index.php' metod='POST' id='form'>
    <label>Save Chapter</label>
    <input type='text' name='chapter' id='input'>
    <input type='text' name='scroll' id='scroll'>
    <input type='submit' value='Save' id='click'>
    </form>";


    foreach ($files as $key => $file) {
        if ($key > 2) {
            $isRead = "";
            if ($lastChapterRead >= $key) {
                $isRead = "read";
            }
            echo "<div id='chapter' class='$isRead'>";
            require_once("text/$file");
            echo "<button id='saveChapter' onclick='saveChapter($key)'>Bookmark</button>";
            echo "</div>";
        }
    }
    ?>
    <script>
        function saveChapter(key) {
            let scrollDistance =
                window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop;

            document.getElementById("input").value = key;
            document.getElementById("scroll").value = scrollDistance;
            document.getElementById("click").click();
        }

        function getParamFromUrl(get) {
            let getParams = window.location.search;

            const urlParams = new URLSearchParams(getParams);
            const id = urlParams.get(get);

            return id;
        }

        function toScroll() {
            let stateCheck = setInterval(() => {
                if (document.readyState === "complete") {
                    if (getParamFromUrl("scroll")) {
                        window.scrollTo(0, getParamFromUrl("scroll"));
                    }
                    clearInterval(stateCheck);
                }
            }, 100);
        }
        toScroll();
    </script>
</body>

</html>