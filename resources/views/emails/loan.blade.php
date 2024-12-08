<p>Olá {{ $user['name'] }},</p>
<p>Seu empréstimo foi processado com sucesso.</p>
<p><strong>Detalhes:</strong></p>
<ul>
    <li>Data de empréstimo: {{ $loanDetails['loan_date'] }}</li>
    <li>Data de devolução: {{ $loanDetails['return_date'] }}</li>
</ul>
<p>Obrigado por usar nossos serviços!</p>
