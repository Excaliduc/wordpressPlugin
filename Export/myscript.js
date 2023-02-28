console.log("hello boy");
jQuery(document).ready(function($) {
    $('#download-xml').click(function (e){
        var name = document.querySelector('#name');
        var reference = document.querySelector('#reference');
        var orderby = document.querySelector('#orderby');
        var asc = $('#asc');

        console.log("appuie détecté !!!");
        let form = $('#myform')
        $.ajax({
            type: 'POST',
            url: my_ajax_object.ajax_url,
            dataType:'text',
            data: {
                action: "download_xml",
                form: form.serialize()
            },
            success: function(response) {
                // Code exécuté en cas de réussite de la requête
                console.log("appuis success");
                // console.log(response);

                var blob = new Blob([response], { type: "application/octetstream" });
                if (window.navigator && window.navigator.msSaveBlob) {
                    // Enregistrement du fichier avec le nom "fichier.txt"
                    window.navigator.msSaveBlob(blob, "database.xml");
                }

                else
                {
                    var url = window.URL || window.webkitURL;
                    link = url.createObjectURL(blob);
                    var a = $("<a />");
                    a.attr("download", "database.xml");
                    a.attr("href", link);
                    $("body").append(a);
                    a[0].click();
                    $("body").remove(a);
                }
            },
            error: function(e) {
                // Code exécuté en cas d'erreur de la requête
                console.log("Il y a une erreur  !");
                console.log(e);
            }
        })
    });
});