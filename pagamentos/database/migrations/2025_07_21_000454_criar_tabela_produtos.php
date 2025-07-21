<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /** tabela criada apenas para mostrar os produtos vendidos de exemplo */
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('preco', 10, 2);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        /** um exemplo da tabela customers está em resources/examples/customers */ 
    Schema::create('customers', function (Blueprint $table) {
            $table->string('id')->primary(); // ID do cliente na API
            $table->string('object')->default('customer');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('mobilePhone')->nullable();
            $table->string('address')->nullable();
            $table->string('addressNumber')->nullable();
            $table->string('complement')->nullable();
            $table->string('province')->nullable();
            $table->string('postalCode')->nullable();
            $table->string('cpfCnpj')->unique();
            $table->enum('personType', ['FISICA', 'JURIDICA']);
            $table->boolean('deleted')->default(false);
            $table->text('additionalEmails')->nullable();
            $table->string('externalReference')->nullable();
            $table->boolean('notificationDisabled')->default(false);
            $table->text('observations')->nullable();
            $table->string('municipalInscription')->nullable();
            $table->string('stateInscription')->nullable();
            $table->boolean('canDelete')->default(true);
            $table->text('cannotBeDeletedReason')->nullable();
            $table->boolean('canEdit')->default(true);
            $table->text('cannotEditReason')->nullable();
            $table->string('city')->nullable();
            $table->string('cityName')->nullable();
            $table->string('state', 2)->nullable(); // ISO 3166-2 code
            $table->string('country', 50)->default('Brasil');
            $table->timestamps();
    });

        /*O exemplo do json do pagamento está em resources/examples/pagamento.json*/ 
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('pagamento_id')->unique(); // ID do pagamento na API
            $table->string('object')->default('payment');
            $table->date('dateCreated')->default(now());
            $table->string('customer'); // ID do cliente na API
            $table->string('checkoutSession')->nullable();
            $table->string('paymentLink')->nullable();
            $table->decimal('value', 10, 2);
            $table->decimal('netValue', 10, 2)->nullable();
            $table->decimal('originalValue', 10, 2)->nullable();
            $table->decimal('interestValue', 10, 2)->nullable();
            $table->string('description')->nullable();
            $table->enum('billingType', ['BOLETO', 'CREDIT_CARD', 'PIX']);
            $table->boolean('canBePaidAfterDueDate')->default(true);
            $table->string('pixTransaction')->nullable();
            $table->enum('status', ['PENDING', 'PAID', 'CANCELED', 'OVERDUE', 'REFUNDED']);
            $table->date('dueDate');
            $table->date('originalDueDate')->nullable();
            $table->date('paymentDate')->nullable();
            $table->date('clientPaymentDate')->nullable();
            $table->integer('installmentNumber')->nullable();
            $table->string('invoiceUrl')->nullable();
            $table->string('invoiceNumber')->nullable();
            $table->string('externalReference')->nullable();
            $table->boolean('deleted')->default(false);
            $table->boolean('anticipated')->default(false);
            $table->boolean('anticipable')->default(true);
            $table->date('creditDate')->nullable();
            $table->date('estimatedCreditDate')->nullable();
            $table->string('transactionReceiptUrl')->nullable();
            $table->string('nossoNumero')->nullable();
            $table->string('bankSlipUrl')->nullable();
            $table->date('lastInvoiceViewedDate')->nullable();
            $table->date('lastBankSlipViewedDate')->nullable();
            $table->json('discount')->nullable(); // JSON para armazenar desconto
            $table->json('fine')->nullable(); // JSON para armazenar multa
            $table->json('interest')->nullable(); // JSON para armazenar juros
            $table->boolean('postalService')->default(false);
            $table->string('custody')->nullable();
            $table->string('escrow')->nullable();
            $table->json('refunds')->nullable(); // JSON para armazenar reembolsos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('produtos');
    }
};
