<html>
<head>
    <?php
    $ocity = str_replace(' ', '', base64_decode($_GET['ocity']));
    $dcity = str_replace(' ', '', base64_decode($_GET['dcity']));
    ?>
    <style>
        .table-fancy {
            border-color: #e2e2e2;
        }

        .table-fancy tr td {
            padding: 15px;
        }

        .table-fancy tr th {
            padding: 15px;
        }

        .price {
            font-size: 20px;
            font-weight: bold;
        }

    </style>
</head>
<body>
<div class="spe-results">
    <table border="1" cellspacing="0" class="table-fancy">
        <tbody>
        <tr>
            <th colspan="6">Previous Records</th>
        </tr>
        <tr>
            <th>Order Id</th>
            <th>Vehicles</th>
            <th>Date</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Booked Price</th>
        </tr>

        <?php
        if(count($data2) > 0) { ?>
        <?php

        foreach ($data2 as $val3) {
        // $order2 = (array)$val3;
        ?>
        <tr class="spe-results-awesome">
            <td><?php echo $val3->id ?></td>
            <td class="title">
                <?php $ymk = explode('*^-', $val3->ymk);?>
                    @foreach($ymk as $val2)
                        @if($val2)
                            {{$val2}} <br>
                        @endif
                    @endforeach
            </td>
            <td class="rel">
                <?php
                echo \Carbon\Carbon::parse($val3->created_at)->format('M,d Y h:iA');
                ?>
            </td>
            <td class="from">
                <?php
                echo $val3->originzsc;
                ?>
            </td>
            <td class="to">
                <?php
                echo $val3->destinationzsc;
                ?>
            </td>
            <td class="price">$<?php echo $val3->payment ?? 0; ?></td>
        </tr>
        <?php
        }
        }
        ?>

        </tbody>
    </table>

</div>
</body>
</html>