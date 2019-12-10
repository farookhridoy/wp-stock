<div class="wrap">
    <h2>Scrap Data List</h2>

    <form method="post">
        
    <?php 

       

       $html = file_get_html($url);
       
       $tds = $html->find('table',0)->find('td');
       $num = '';
       foreach($tds as $td){

         if($td->plaintext == 'Consensus Rating:'){

            $next_td = $td->next_sibling();
            $num = $next_td->plaintext ;    
            break; 
        }
    }

    echo($num);

    ?>
    </form>
</div>