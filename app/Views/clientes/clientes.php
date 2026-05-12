
    <div>
        <p> <h2> 
            <?php 
            echo $titulo;
            ?>
        </h2> </p>

        <table id='tablaclientes'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente)
                    {   echo 
                            "
                            <tr>
                                <td>".$cliente["nombre"]."</td>
                                <td>".$cliente["apellido"]."</td>                            
                            </tr>";
                    }    
                ?>
            </tbody>
        </table>
    </div>