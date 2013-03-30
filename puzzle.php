<?php
//���ݒ�t�@�C���ǂݍ���
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
    $mode_Form = $_POST["mode"]; //1:�₳����2:����3:���
}
if(empty($_GET["link"])){
    $link = "";
}else{
    $link = $_GET["link"]; //�����������N��
}
if (empty($_SESSION["game"]) || $button == "GIVEUP") {
    $g = new Puzzle(9);
    $_SESSION["game"] = serialize($g);
} else {
    $g = unserialize($_SESSION["game"]);
}
if ($button == "START") {
    //����
    $g->status = 1; //�����_���\��
    //��Փx�ݒ�
    $g->mode = $mode_Form; //�I��������Փx��ޔ�(�\���p�j
    if ($mode_Form == 1) {
        $shfull = 3;
    } elseif ($mode_Form == 2) {
        $shfull = 6;
    } elseif ($mode_Form == 3) {
        $shfull = 10;
    }

    for ($i = 0; $i <= $g->souwaku * $shfull; $i++) {
        //�����_���ɓ�����
        $g->random_move();
    }
    if ($g->chk_complate()) {
        //����߂������񓮂���
        $g->random_move();
    }
}

//������
$g->move($link);
//�\���֘A
if ($g->mode == 1) {
    print("-�₳����-");
} elseif ($g->mode == 2) {
    print("-����-");
} elseif ($g->mode == 3) {
    print("-���-");
}
if (!$g->chk_complate()) {
    print("������");
    $g->status = 3;
} else {
    print("����");
    $g->status = 0;
}
?>
<html>
    <head>
        <title>
            �P�T�Q�[���v���O����Ver2.00
        </title>
    </head>
    <body>
        <table border=0>
            <tr>
                <td>
                    <table border=1>
                        <?php
                        $k = 0;
                        $g->set_move(); //����������W�m��
                        for ($i = 0; $i < $g->waku; $i++) {
                            ?>
                            <TR>
                                <?php
                                for ($j = 0; $j < $g->waku; $j++) {
                                    ?>
                                    <TD>
                                        <?php
                                        $k++;
                                        $out = "<img src=" . $dir . $g->gamen[$k] . "." . $ex . ">"; //�摜�̂ݕ\��
                                        foreach ($g->kouho as $value) {
                                            //��������ӏ��������烊���N�𒣂�
                                            if ($k == $value) {
                                                $out = "<a href=" . $phpname . "?link=" . $k . "><img src=" . $dir . $g->gamen[$k] . "." . $ex . "></a>"; //�����N�𒣂�
                                            }
                                        }
                                        print ($out); //�摜�������N���o��
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
                <TD>�@</TD>
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
                <input type="radio" name="mode" value="1">�₳����
                <input type="radio" name="mode" value="2" checked>����
                <input type="radio" name="mode" value="3">���
                <br />
                <input type="hidden" name="button" value="START">
                <INPUT type="submit" name="submit" value="�X�^�[�g">
                <?php
            } else {
                ?>
                <input type="hidden" name="button" value="GIVEUP">
                <INPUT type="submit" name="submit" value="�M�u�A�b�v">
                <?php
            }
            $_SESSION["game"] = serialize($g);
            ?>
        </FORM>
    </body>
</html>