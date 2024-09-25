<html>

</html>


<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Confrimation IAP</title>
  <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
</head>

<body>
  <div id="confirmation" style="max-width: 70%;text-align: left;margin:auto;">
    <div class="row">
      <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        <a href="https://iap.dz" target="_blank"><img width="80px"
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Sonatrach.svg/150px-Sonatrach.svg.png"
            title="Sonatrach" alt="Sonatrach"></a>
      </div>
      <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
        <h3>Nous vous remercions, {{$saved->nom}} {{$saved->prenom}} pour votre inscription.
        </h3>
      </div>
    </div>
    <h4 style="color: red;font-weight: bold; ">Résumé du formulaire d'inscription au concour national de recrutement SONATRACH
    </h4>
    <table class="table table-striped">
      <tbody>
        <tr>
          <td style="color: black;font-weight: bold; ">Nom :</td>
          <td>{{$saved->nom}}</td>
        </tr>
        <tr>
          <td style="color: black;font-weight: bold; ">Prénom :</td>
          <td>{{$saved->prenom}}</td>
        </tr>
        <tr>
          <td style="color: black;font-weight: bold; ">Date de naissance :</td>
          <td>{{$saved->datenaiss}}</td>
        </tr>
        <!-- <tr>
          <td style="color: black;font-weight: bold; ">Addresse Personnelle :</td>
          <td>{{$saved->adresse}}</td>
        </tr> -->
        <tr>
          <td style="color: black;font-weight: bold; ">Téléphone :</td>
          <td>{{$saved->phone}}</td>
        </tr>
        <tr>
          <td style="color: black;font-weight: bold; ">E-Mail :</td>
          <td>{{$saved->email}}</td>
        </tr>
        <tr>
          <td style="color: black;font-weight: bold; ">Type diplôme :</td>
          <td>{{$data['diplom']}}</td>
        </tr>
        <tr>
          <td style="color: black;font-weight: bold; ">Spécialite :</td>
          <td>{{$data['specdiplome']}}</td>
        </tr>
        <tr>
          <td style="color: black;font-weight: bold; ">Poste à concourir :</td>
          <td>{{$data['speciliteform']}}</td>
        </tr>
        <tr>
          <td style="color: black;font-weight: bold; ">Date et heure d’inscription :</td>
          <td>{{$saved->created}}</td>
        </tr>
        <tr>
          <td style="color: black;font-weight: bold; ">Votre Numéro d’inscription :</td>
          <td>{{$saved->numinscritanem}}</td>
        </tr>
        <!-- <tr>
          <td  style="color: black;font-weight: bold; ">Votre Centre d'Examen : </td>
          <td>{{$saved->centreExamen}}</td>
        </tr> -->
      </tbody>
    </table>
  </div>
  <button onclick="printDiv('confirmation')">Imprimer</button>

  <h4 style="color: red;font-weight: bold; ">N.B : N’oubliez pas d’imprimer le présent formulaire en appuyant
    simultanément sur les deux boutons ctrl+p .
    <!-- en cliquant simultanément sur les boutons -->
    <!-- <img style="height: 40px;width: 150px" src="http://www.clker.com/cliparts/S/F/y/h/G/9/ctrl-p-hi.png" title="ctrl+p" alt="ctrl+p" /> . -->
  </h4>
  <!--     <div style="text-align: center;width: 450px;margin: auto;;" >
      <input type="button" class="btn btn-primary btn-lg"
      value=" Imprimer votre formulaire d’inscription" onclick="javascript:printDiv('confirmation')" />
    </div> -->
  <script language="javascript" type="text/javascript">

    document.addEventListener("keydown", function (e) {
      // Check for Ctrl + P (or Cmd + P)
      if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault(); // Prevent default print behavior
        printDiv('confirmation'); // Call the custom print function
      }
    });

    function printDiv(divID) {
      // Get the HTML of the div
      var divElements = document.getElementById(divID).innerHTML;

      // Open a new window
      var printWindow = window.open('', '', 'height=600,width=800');

      // Write the contents of the div to the new window
      printWindow.document.write('<html><head><title>Print</title>');
      printWindow.document.write('<style>/* Add any styles needed for printing */</style>');
      printWindow.document.write('</head><body>');
      printWindow.document.write(divElements);
      printWindow.document.write('</body></html>');

      // Close the document to trigger the print preview
      printWindow.document.close();

      // Wait for the content to load and then print
      printWindow.onload = function () {
        printWindow.focus(); // Required for some browsers
        printWindow.print();
        printWindow.close(); // Close the window after printing
      };
    }

  </script>
</body>

</html>