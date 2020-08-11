<?php

// クロスサイトスクリプティング(XSS)対策
    function h($s) {
        return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }
?>