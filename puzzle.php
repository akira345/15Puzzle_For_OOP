<?php
//環境設定ファイル読み込み
require_once("./15puzzle.ini");
require_once("./puzzle.class.php");
////
session_start();
if(empty($_POST["button"])){
    $button = "";
}else{
    $button = $_POST["button"]; 
}
if(empty($_POST["mode"])){
    $mode_Form = 0;
}else{
    $mode_Form = $_POST["mode"]; //1:やさしい2:普通3:難しい
}
if(empty($_GET["link"])){
    $link = "";
}else{
    $link = $_GET["link"]; //動かすリンク先
}
if (empty($_SESSION["game"]) || $button == "GIVEUP") {
    $g = new Puzzle(9);
    $_SESSION["game"] = serialize($g);
} else {
    $g = unserialize($_SESSION["game"]);
}
if ($button == "START") {
    //初回
    $g->status = 1; //ランダム表示
    //難易度設定
    $g->mode = $mode_Form; //選択した難易度を退避(表示用）
    if ($mode_Form == 1) {
        $shfull = 3;
    } elseif ($mode_Form == 2) {
        $shfull = 6;
    } elseif ($mode_Form == 3) {
        $shfull = 10;
    }

    for ($i = 0; $i <= $g->souwaku * $shfull; $i++) {
        //ランダムに動かす
        $g->random_move();
    }
    if ($g->chk_complate()) {
        //万一戻ったら一回動かす
        $g->random_move();
    }
}

//動かす
$g->move($link);
//表示関連
if ($g->mode == 1) {
    print("-やさしい-");
} elseif ($g->mode == 2) {
    print("-普通-");
} elseif ($g->mode == 3) {
    print("-難しい-");
}
if (!$g->chk_complate()) {
    print("未完成");
    $g->status = 3;
} else {
    print("完成");
    $g->status = 0;
}
?>
<html>
    <head>
        <title>
            １５ゲームプログラムVer2.00
        </title>
    </head>
    <body>
        <table border=0>
            <tr>
                <td>
                    <table border=1>
                        <?php
                        $k = 0;
                        $g->set_move(); //動かせる座標確定
                        for ($i = 0; $i < $g->waku; $i++) {
                            ?>
                            <TR>
                                <?php
                                for ($j = 0; $j < $g->waku; $j++) {
                                    ?>
                                    <TD>
                                        <?php
                                        $k++;
                                        $out = "<img src=" . $dir . $g->gamen[$k] . "." . $ex . ">"; //画像のみ表示
                                        foreach ($g->kouho as $value) {
                                            //動かせる箇所だったらリンクを張る
                                            if ($k == $value) {
                                                $out = "<a href=" . $phpname . "?link=" . $k . "><img src=" . $dir . $g->gamen[$k] . "." . $ex . "></a>"; //リンクを張る
                                            }
                                        }
                                        print ($out); //画像＆リンクを出力
                                        ?>
                                    </TD>
                                    <?php
                                }
                                ?>
                            </TR>
                            <?php
                        }
                        ?>
                    </TABLE>
                </TD>
                <TD>　</TD>
                <TD>
                <TABLE BORDER="1">
                        <?php
                        $k = 0;
                        for ($i = 0; $i < $g->waku; $i++) {
                            ?>
                            <TR>
                                <?php
                                for ($j = 0; $j < $g->waku; $j++) {
                                    $k++;
                                    ?>
                                    <TD>
                                        <?php
                                        $out = "<img src=" . $dir . $k . "." . $ex . ">";
                                        print ($out);
                                        ?>
                                    </TD>
                                    <?php
                                }
                                ?>
                            </TR>
                            <?php
                        }
                        ?>
                    </TABLE>
                </TD>
            </TR>
        </TABLE>
        <FORM method="POST" action="<?= $phpname ?>">
            <?php
            if ($g->status != 3) {
                ?>
                <input type="radio" name="mode" value="1">やさしい
                <input type="radio" name="mode" value="2" checked>普通
                <input type="radio" name="mode" value="3">難しい
                <br />
                <input type="hidden" name="button" value="START">
                <INPUT type="submit" name="submit" value="スタート">
                <?php
            } else {
                ?>
                <input type="hidden" name="button" value="GIVEUP">
                <INPUT type="submit" name="submit" value="ギブアップ">
                <?php
            }
            $_SESSION["game"] = serialize($g);
            ?>
        </FORM>
    </body>
</html>