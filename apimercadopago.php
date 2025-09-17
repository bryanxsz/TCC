<script src="https://sdk.mercadopago.com/js/v2"></script>

<div id="form-checkout"></div>

<script>
  const mp = new MercadoPago('YOUR_PUBLIC_KEY', {
    locale: 'pt-BR'
  });

  const cardForm = mp.cardForm({
    amount: "1000.00",
    autoMount: true,
    form: {
      id: "paymentForm",
      cardNumber: {
        id: "form-checkout__cardNumber",
        placeholder: "Número do cartão",
      },
      expirationDate: {
        id: "form-checkout__expirationDate",
        placeholder: "MM/AA",
      },
      securityCode: {
        id: "form-checkout__securityCode",
        placeholder: "CVV",
      },
      cardholderName: {
        id: "form-checkout__cardholderName",
        placeholder: "Nome no cartão",
      },
      issuer: {
        id: "form-checkout__issuer",
        placeholder: "Banco emissor",
      },
      installments: {
        id: "form-checkout__installments",
        placeholder: "Parcelas",
      },
      identificationType: {
        id: "form-checkout__identificationType",
        placeholder: "Tipo de documento",
      },
      identificationNumber: {
        id: "form-checkout__identificationNumber",
        placeholder: "Número do documento",
      },
      email: {
        id: "form-checkout__email",
        placeholder: "Email",
      },
    },
    callbacks: {
      onFormMounted: error => {
        if (error) return console.warn("Form Mounted handling error: ", error);
        console.log("Form mounted");
      },
      onSubmit: event => {
        event.preventDefault();

        const {
          paymentMethodId,
          issuerId,
          cardholderEmail,
          amount,
          token,
          installments,
          identificationNumber,
          identificationType,
        } = cardForm.getCardFormData();

        fetch('/seu-backend.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({
            token: token,
            email: cardholderEmail,
            amount: amount,
            payment_method_id: paymentMethodId,
            issuer_id: issuerId,
            installments: installments,
            identification_number: identificationNumber,
            identification_type: identificationType,
          }),
        })
        .then(response => response.json())
        .then(data => {
          alert('Pagamento realizado com sucesso! Order ID: ' + data.order_id);
        })
        .catch(error => {
          alert('Erro no pagamento: ' + error.message);
        });
      },
      onFetching: (resource) => {
        console.log("Fetching resource: ", resource);
        // Pode mostrar loading aqui
      },
    },
  });
</script>