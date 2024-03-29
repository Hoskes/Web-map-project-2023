<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div id='poly-container'></div>
<div id='count-z'></div>

<script>
    
    datasetNo = 62541;


    $(document).ready(function() {
        function sendToDB(jsonData, numOfRecords) {
            $.ajax({
                type: "POST",
                url: "callInsertJSONProcedure.php",
                data: {
                    "json": JSON.stringify(jsonData),
                    "counter": numOfRecords,
                    "dataset": datasetNo
                },
                success: function(response) {
                    // Обработка успешного ответа
                    // console.log(numOfRecords);
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Обработка ошибки
                    console.log(error);
                }
            });
        }



        let num = 10;
        $.ajax({
            url: 'https://apidata.mos.ru/v1/datasets/' + datasetNo + '/count?$orderby=global_id&api_key=78a7232a-c378-44d7-bcd4-75dd6e24efdc',
            method: 'get',
            async: false, 
            success: function(data) {
                // Обработка ответа от сервера
                num = data;

            }
        });
        console.log(num);




        function downloadDataToDB(skip, top) {
            $.ajax({
                url: 'https://apidata.mos.ru/v1/datasets/' + datasetNo + '/rows?$orderby=global_id&api_key=78a7232a-c378-44d7-bcd4-75dd6e24efdc' + '&$skip=' + skip + '&$top=' + top,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    
                    if (response.length > 0) {
                        console.log('success');
                        sendToDB(response, top);
                    } else {
                        console.log('empty');
                    }
                },
                error: function(response) {
                    $('#poly-container').html("ERROR");
                }
            });
        }
        

        skip = 0;
        downloadPerTime = 100;
        while (num > 0) {
            if (num < downloadPerTime) {
                downloadDataToDB(skip, num);
                num = 0;
            } else {
                downloadDataToDB(skip, downloadPerTime);
                num -= downloadPerTime;
            }
            skip += downloadPerTime;
        }
        console.log('SUCCESS');
    });
</script>