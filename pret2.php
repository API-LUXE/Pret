<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Confirmation de Prêt</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    /* Styles identiques à votre version précédente */
    body {
      margin: 0;
      padding: 33px 10px;
      font-family: 'Poppins', sans-serif;
      background-color: #e5e5e5;
      color: #222;
    }
    .document-container {
      background: white;
      max-width: 800px;
      margin: auto;
      padding: 40px 60px;
      border: 1px solid #ccc;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      position: relative;
    }
    header {
      text-align: center;
      margin-bottom: 40px;
    }
    .logo {
      width: 80px;
      height: auto;
      margin-bottom: 10px;
    }
    h1 {
      font-size: 24px;
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #333;
      font-weight: 600;
    }
    #message {
      font-size: 18px;
      line-height: 1.1;
      white-space: pre-wrap;
      margin-bottom: 40px;
    }
    .signature-section {
      margin-top: 40px;
    }
    .signature-label {
      font-weight: 600;
      margin-bottom: 8px;
    }
    canvas {
      border: 1px solid #999;
      width: 100%;
      height: 200px;
      border-radius: 4px;
      touch-action: none;
    }
    .signature-buttons {
      margin-top: 10px;
      text-align: right;
    }
    .signature-buttons button {
      padding: 8px 16px;
      font-size: 14px;
      margin-left: 10px;
      border: none;
      border-radius: 4px;
      background-color: #007bff;
      color: white;
      cursor: pointer;
    }
    .signature-buttons button:hover {
      background-color: #0056b3;
    }
    footer {
      margin-top: 60px;
      text-align: center;
      font-size: 12px;
      color: #888;
    }
    @media screen and (max-width: 600px) {
      .document-container {
        padding: 30px 20px;
      }
      h1 {
        font-size: 15px;
      }
      #message {
        font-size: 15px;
      }
      canvas {
        height: 150px;
      }
    }
  </style>
</head>
<body>
  <div class="document-container">
    <header>
      <img src="image/ld.png" alt="Logo" class="logo" />
      <div style="text-align:right; margin-bottom: 20px;">
        <label for="languageSelect" style="font-weight: 600;">Langue :</label>
        <select id="languageSelect" onchange="changeLanguage()" style="padding: 5px; border-radius: 4px;">
          <option value="fr">Français</option>
          <option value="de">Deutsch</option>
          <option value="es">Español</option>
          <option value="pt">Português</option>
        </select>
      </div>
      <h1>Confirmation de Demande de Prêt</h1>
    </header>

    <p id="message">Chargement...</p>

    <div style="display:flex; justify-content: space-around; margin-bottom: 20px;">
      <img style="width:20%;" src="image/imga.png">
      <img style="width:15%;" src="image/imgb.png">
      <img style="width:25%;" src="image/sgn.png">
    </div>

    <div id="uploadedImages" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; justify-content: center;"></div>

    <div class="signature-section">
      <div class="signature-label">Signature du client :</div>
      <canvas id="signatureCanvas"></canvas>
      <div class="signature-buttons">
        <button onclick="clearSignature()">Effacer</button>
      </div>
    </div>

    <footer>
      Document généré automatiquement — Merci de votre confiance.
    </footer>
  </div>

  <div style="text-align: center; margin-top: 30px;">
    <button onclick="window.history.back()" style="
      padding: 10px 20px;
      background-color: #6c757d;
      color: white;
      border: none;
      border-radius: 4px;
      margin-right: 10px;
      cursor: pointer;
    ">← Retour</button>

    <button onclick="downloadPDF()" style="
      padding: 12px 24px;
      font-size: 16px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    ">📄 Télécharger en PDF</button>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    const data = JSON.parse(localStorage.getItem('loanData'));
    if (!data) {
      alert("Aucune donnée trouvée. Redirection...");
      window.location.href = 'pret1.php';
    } else {
      document.getElementById('message').textContent =
        `N° de REF:00012672\nFait à la date du ..../..../2025\n\n` +
        `Cher ${data.name}, en signant ce contrat de prêt, vous acceptez avoir demandé un prêt financier de ${data.amount} $ chez la banque LenddoEFL. ` +
        `Vous rembourserez ${data.totalRepayment} $, pendant ${data.duration} mois.\n\n` +
        `À retenir :\n` +
        `- Taux d'intérêt 5%\n` +
        `- Montant de remboursement ${data.totalRepayment} $\n` +
        `- Montant de remboursement ${data.monthlyRepayment} $ par mois\n` +
        `- Période de remboursement ${data.duration} mois\n` +
        `- Numéro de compte pour le versement mensuel BE0167218499212490003`;
    }

    const translations = {
      fr: {
        title: "Confirmation de Demande de Prêt",
        message: data =>
          `N° de REF:00012672\nFait à la date du ..../..../2025\n\n` +
          `Cher ${data.name}, en signant ce contrat de prêt, vous acceptez avoir demandé un prêt financier de ${data.amount} $ chez la banque LenddoEFL. ` +
          `Vous rembourserez ${data.totalRepayment} $, pendant ${data.duration} mois.\n\n` +
          `À retenir :\n` +
          `- Taux d'intérêt 5%\n` +
          `- Montant de remboursement ${data.totalRepayment} $\n` +
          `- Montant de remboursement ${data.monthlyRepayment} $ par mois\n` +
          `- Période de remboursement ${data.duration} mois\n` +
          `- Numéro de compte pour le versement mensuel BE0167218499212490003`,
        signature: "Signature du client :",
        download: "📄 Télécharger en PDF",
        footer: "Document généré automatiquement — Merci de votre confiance."
      },
      de: {
        title: "Bestätigung des Darlehensantrags",
        message: data =>
          `Referenznummer: 00012672\nAusgestellt am ..../..../2025\n\n` +
          `Sehr geehrter ${data.name}, durch die Unterzeichnung dieses Darlehensvertrags bestätigen Sie, ein Darlehen über ${data.amount} $ bei LenddoEFL beantragt zu haben. ` +
          `Sie werden insgesamt ${data.totalRepayment} $ über ${data.duration} Monate zurückzahlen.\n\n` +
          `Zu beachten:\n- Zinssatz 5%\n- Gesamtrückzahlung ${data.totalRepayment} $\n- Monatliche Rückzahlung ${data.monthlyRepayment} $\n- Rückzahlungsdauer ${data.duration} Monate\n- Einzahlungskonto: BE0167218499212490003`,
        signature: "Unterschrift des Kunden:",
        download: "📄 Als PDF herunterladen",
        footer: "Dokument automatisch erstellt – Vielen Dank für Ihr Vertrauen."
      },
      es: {
        title: "Confirmación de Solicitud de Préstamo",
        message: data =>
          `N.º de REF: 00012672\nHecho el ..../..../2025\n\n` +
          `Estimado/a ${data.name}, al firmar este contrato de préstamo, usted acepta haber solicitado un préstamo financiero de ${data.amount} $ en el banco LenddoEFL. ` +
          `Usted reembolsará ${data.totalRepayment} $ durante ${data.duration} meses.\n\n` +
          `A tener en cuenta:\n- Tasa de interés 5%\n- Total a devolver: ${data.totalRepayment} $\n- Pago mensual: ${data.monthlyRepayment} $\n- Plazo: ${data.duration} meses\n- Cuenta de pago: BE0167218499212490003`,
        signature: "Firma del cliente:",
        download: "📄 Descargar en PDF",
        footer: "Documento generado automáticamente — Gracias por su confianza."
      },
      pt: {
        title: "Confirmação do Pedido de Empréstimo",
        message: data =>
          `Nº REF: 00012672\nFeito em ..../..../2025\n\n` +
          `Caro(a) ${data.name}, ao assinar este contrato de empréstimo, você confirma ter solicitado um empréstimo de ${data.amount} $ ao banco LenddoEFL. ` +
          `Você reembolsará ${data.totalRepayment} $ ao longo de ${data.duration} meses.\n\n` +
          `Lembrete:\n- Taxa de juros: 5%\n- Valor total a reembolsar: ${data.totalRepayment} $\n- Pagamento mensal: ${data.monthlyRepayment} $\n- Período: ${data.duration} meses\n- Conta para pagamento: BE0167218499212490003`,
        signature: "Assinatura do cliente:",
        download: "📄 Baixar PDF",
        footer: "Documento gerado automaticamente — Obrigado pela sua confiança."
      }
    };

    function changeLanguage() {
      const lang = document.getElementById('languageSelect').value;
      const data = JSON.parse(localStorage.getItem('loanData'));
      if (!translations[lang]) return;

      document.querySelector('h1').textContent = translations[lang].title;
      document.getElementById('message').textContent = data
        ? translations[lang].message(data)
        : "Aucune information disponible.";

      document.querySelector('.signature-label').textContent = translations[lang].signature;
      document.querySelector('.signature-buttons button').textContent = "Effacer";
      document.querySelector('footer').textContent = translations[lang].footer;
      document.querySelector('button[onclick="downloadPDF()"]').textContent = translations[lang].download;
    }

    window.addEventListener('DOMContentLoaded', changeLanguage);

    const uploaded = JSON.parse(localStorage.getItem('loanImages')) || [];
    const container = document.getElementById('uploadedImages');
    uploaded.forEach(dataUrl => {
      const img = document.createElement('img');
      img.src = dataUrl;
      img.style.maxWidth = '20%';
      img.style.border = '1px solid #ccc';
      img.style.borderRadius = '4px';
      img.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
      container.appendChild(img);
    });

    function convertCanvasToImage() {
      const canvas = document.getElementById('signatureCanvas');
      const dataURL = canvas.toDataURL('image/png');
      const image = new Image();
      image.src = dataURL;
      image.style.width = canvas.offsetWidth + 'px';
      image.style.height = canvas.offsetHeight + 'px';
      image.style.border = '1px solid #999';
      image.style.borderRadius = '4px';
      image.className = 'canvas-replacement';
      canvas.style.display = 'none';
      canvas.parentNode.insertBefore(image, canvas);
    }

    function restoreCanvas() {
      const image = document.querySelector('.canvas-replacement');
      if (image) image.remove();
      const canvas = document.getElementById('signatureCanvas');
      if (canvas) canvas.style.display = 'block';
    }

    function downloadPDF() {
      convertCanvasToImage();
      const element = document.querySelector('.document-container');
      const opt = {
        margin: 0,
        filename: 'confirmation_pret.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
          scale: 2,
          scrollY: 0,
          useCORS: true,
          allowTaint: true
        },
        jsPDF: { unit: 'pt', format: 'a4', orientation: 'portrait' },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
      };
      html2pdf().set(opt).from(element).save().then(() => {
        restoreCanvas();
      });
    }

    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    let drawing = false;

    function resizeCanvas() {
      const ratio = window.devicePixelRatio || 1;
      const displayWidth = canvas.clientWidth;
      const displayHeight = 200;

      canvas.style.height = displayHeight + 'px';
      canvas.height = displayHeight * ratio;
      canvas.width = displayWidth * ratio;
      ctx.setTransform(1, 0, 0, 1, 0, 0);
      ctx.scale(ratio, ratio);
      ctx.lineWidth = 2;
      ctx.lineCap = 'round';
      ctx.strokeStyle = '#222';
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    function getPos(e) {
      const rect = canvas.getBoundingClientRect();
      const clientX = e.touches ? e.touches[0].clientX : e.clientX;
      const clientY = e.touches ? e.touches[0].clientY : e.clientY;
      return { x: clientX - rect.left, y: clientY - rect.top };
    }

    function startDrawing(e) {
      drawing = true;
      const pos = getPos(e);
      ctx.beginPath();
      ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
      if (!drawing) return;
      const pos = getPos(e);
      ctx.lineTo(pos.x, pos.y);
      ctx.stroke();
    }

    function stopDrawing() {
      drawing = false;
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', (e) => {
      e.preventDefault();
      draw(e);
    }, { passive: false });
    canvas.addEventListener('touchend', stopDrawing);

    function clearSignature() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
  </script>
</body>
</html>