<?php

if(isset($argv[1])){
    $htmlFile = $argv[1];

    if(is_file($htmlFile)){
        $ext = explode('.', $htmlFile);
        if($ext[1] == "html"){

            $content = file_get_contents($htmlFile);

            preg_match_all("!<a class=title( [^>]+)?>(.*)</a>!Ui", $content, $a, PREG_PATTERN_ORDER);
            preg_match_all("!</span( [^>]+)?>(.*)</span>!Ui", $content, $span, PREG_PATTERN_ORDER);

            static $count = 0;
            static $data = array();
            
            foreach($span[0] as $key){
                $date = explode("</span>", $key);

                if($date[1] != "" && str_contains($date[1], "<span class=\"k-icon k-i-loading\" style=display:none>") == null){
                    $title = multiexplode(array("<bdi>","</bdi>"),$a[2][$count]);

                    $current_data = file_get_contents("result.json");
                    array_push($data,['title'=>$title[1], 'release_date'=>$date[1]]);
                    $json = json_encode($data);

                    echo "============================\n";
                    echo "Titre: ".$title[1]."\n";
                    
                    echo "Date de sortie: ".$date[1]."\n";
                    echo "============================\n";
                    $count++;
                }
            }

            $bytes = file_put_contents("result.json", $json); 

        }
        else{
            echo "File must be .html\n";
        }
    }
    else{
        echo "File not founded.\n";
    }
}else{
    echo "1 argument is missing!\n";
}

function multiexplode ($delimiters,$string) {

    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}
?>