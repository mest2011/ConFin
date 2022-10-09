<?php


echo "<script src=\"../view/js/scripts.js\"></script>";
echo "<script src=\"../view/js/menu-lateral.js\"></script>";
echo '<script src="js/xlsx.full.min.js"></script>
<script type="text/javascript" src="js/FileSaver.min.js"></script>';

echo "<script>
        function s2ab(s) {
                    let buf = new ArrayBuffer(s.length);
                    let view = new Uint8Array(buf);
                    for (let i = 0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                        return buf;
                }
                
        
        </script>";

?>
