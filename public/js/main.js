$(document).ready(function () {
    // function callback
    setting();
    newClient();
    // newContract();
    clientSearch();
    
    $('.datatables').DataTable({
        "order": [[0, 'desc']]
    }).on('draw', function () {
    });
    
    updatePaymentStatusInvoice();
    updateExportedInvoice();
    setCollapseSidebar();
});

function newContract() {
    $('#newContractModal').on('shown.bs.modal', function (e) {
        
        var mySwiper = new Swiper('#contract-steps', {
            
            spaceBetween: 30,
            slidesPerView: 1,
            simulateTouch: false,
            
            // If we need pagination
            pagination: {
                el: '.progress',
                bulletClass: 'progress-bar',
                bulletActiveClass: 'progress-bar-striped progress-bar-animated',
                renderBullet: function (index, className) {
                    return '<div class="' + className + '" role="progressbar" aria-valuemin="0" aria-valuemax="100">Step ' + (index + 1) + '</div>';
                },
            },
            // Navigation arrows
            navigation: {
                nextEl: '.next-btn-contract',
                prevEl: '.prev-btn-contract',
            },
        });
        
        $('#newContractModal').on('hidden.bs.modal', function (e) {
            mySwiper.destroy();
        });
    });
};



function newClient() {
    $('#newClientModal').on('shown.bs.modal', function (e) {
        var mySwiper = new Swiper('#client-steps', {
            
            spaceBetween: 30,
            slidesPerView: 1,
            simulateTouch: false,
            
            // If we need pagination
            pagination: {
                el: '.progress',
                bulletClass: 'progress-bar',
                bulletActiveClass: 'progress-bar-striped progress-bar-animated',
                renderBullet: function (index, className) {
                    return '<div class="' + className + '" role="progressbar" aria-valuemin="0" aria-valuemax="100">Step ' + (index + 1) + '</div>';
                },
            },
            
            // Navigation arrows
            navigation: {
                nextEl: '.next-btn-client',
                prevEl: '.prev-btn-client',
            },
        });
        $('#newClientModal').on('hidden.bs.modal', function (e) {
            mySwiper.destroy();
        });
    });
};

function setting() {
    $('[data-toggle="tooltip"]').tooltip();
    $('.tooltip-on').tooltip();
    
    $('.client-item:has(.invoice-status.badge-warning)').addClass("active-warning");
    $('.client-item:has(.invoice-status.badge-danger)').addClass("active-danger");
    $('.client-item.active-warning .client-info h6').prepend('<span class="badge badge-warning"><i class="fas fa-exclamation"></i></span>');
    $('.client-item.active-danger .client-info h6').prepend('<span class="badge badge-danger"><i class="fas fa-exclamation"></i></span>');  
    
    $('#drawer-btn').on('click', function (e) {
        $('.drawer-container').toggleClass("show");
        if (localStorage.getItem("active") == "1") {
            localStorage.setItem("active", "0");
        }
        else {
            localStorage.setItem("active", "1");
        }
    });
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    
}

function setCollapseSidebar() {
    if (localStorage.getItem("active") == "1") {
        $('#drawer-btn').addClass('active');
        $('#page-nav-drawer').removeClass('show');
        $('#page-nav-drawer').removeClass('no-transition');
    }
    else {
        $('#drawer-btn').removeClass('active');
        $('#page-nav-drawer').addClass('show');
        $('#page-nav-drawer').removeClass('no-transition');
    }
}

function clientSearch() {
    $('#client-search').on('keyup', function () {
        $('#search-result').show();
        var key = $(this).val();
        $.ajax({
            method: 'GET',
            url: base_url + '/search',
            data: {
                key: key
            }
        })
        .done(function (data) {
            console.log(data);
            $('#search-result').html(data);
            
            $('#close-search').on('click', function () {
                $('#search-result').hide();
            });
        })
        .fail(function (xhr, status, error) {
            console.log(this.url);
            console.log(error);
        })
    })
}

function updatePaymentStatusInvoice() {
    $('.tick_invoice').on('click', function (e) {
        e.preventDefault();
        var invoice_id = $(this).attr('data-id');
        
        var user_id = $( "input[type=hidden][name=user_id]" ).val();
        var contract_id = $( "input[type=hidden][name=contract_id]" ).val();
        var payment_type = $( "input[type=hidden][name=payment_type]" ).val();
        var payment_status = $( "input[type=hidden][name=payment_status]" ).val();
        var payment_note = $( "input[type=hidden][name=payment_note]" ).val();
        var time = $( "input[type=hidden][name=time]" ).val();
        var exported = $( "input[type=hidden][name=exported]" ).val();
        var old_contract_id = $( "input[type=hidden][name=old_contract_id]" ).val();
        var old_payment_type = $( "input[type=hidden][name=old_payment_type]" ).val();
        var old_payment_status = $( "input[type=hidden][name=old_payment_status]" ).val();
        var old_payment_note = $( "input[type=hidden][name=old_payment_note]" ).val();
        var old_time = $( "input[type=hidden][name=old_time]" ).val();
        var old_exported = $( "input[type=hidden][name=old_exported]" ).val();
        
        console.log(payment_status);
        
        // var result = false;
        var data_id = $(this).data('id');
        var aTag = $(this);
        
        var json = {
            user_id: user_id,
            contract_id: contract_id,
            payment_type: payment_type,
            payment_status: payment_status,
            payment_note: payment_note,
            time: time,
            exported: exported,
            old_contract_id: old_contract_id,
            old_payment_type: old_payment_type,
            old_payment_status: old_payment_status,
            old_payment_note: old_payment_note,
            old_time: old_time,
            old_exported: old_exported,
        };
        var jsonText = JSON.stringify(json);
        
        console.log('jsonText' + jsonText);
        
        // BootstrapDialog.confirm({
        //     title: 'Untick invoice',
        //     message: 'Do you really want to update this invoice\'s status? Your action will be saved to history...',
        //     type: BootstrapDialog.TYPE_DANGER,
        //     callback: function (result) {
        //         if (result) {
                    $.ajax({
                        // url: base_url + '/update-invoice/' + invoice_id + '/' + user_id ,
                        url: base_url + '/update-payment-status-invoice/' + jsonText + '/' + invoice_id ,
                        method: "GET",
                        data: {
                            'invoice_id': invoice_id,
                            // 'paid' : $paid,
                        },
                        async: true,
                    })
                    .done(function (data) {
                        if (data.result) {
                            if(data.paid == 'yes') {
                                $(".invoice-status-"+ invoice_id).addClass('badge-paid');
                            }
                            if(data.paid == 'no') {
                                $(".invoice-status-"+ invoice_id).removeClass('badge-paid');
                            }
                        }
                    })
                    .fail(function (xhr, status, error) {
                        console.log('basic error');
                        if (xhr.status == 422) {
                            alert("Invoice is not ticked yet")
                        } else {
                            console.log(this.url);
                            console.log(error);
                            console.log(xhr.responseText);
                        }
                        // result = false;
                    });
    });
}

function updateExportedInvoice() {
    $('.tick_exported_invoice').on('click', function (e) {
        // e.preventDefault();
        var invoice_id = $(this).attr('data-id');
        
        var user_id = $( "input[type=hidden][name=user_id]" ).val();
        var contract_id = $( "input[type=hidden][name=contract_id]" ).val();
        var payment_type = $( "input[type=hidden][name=payment_type]" ).val();
        var payment_status = $( "input[type=hidden][name=payment_status]" ).val();
        var payment_note = $( "input[type=hidden][name=payment_note]" ).val();
        var time = $( "input[type=hidden][name=time]" ).val();
        var exported = $( "input[type=hidden][name=exported]" ).val();
        var old_contract_id = $( "input[type=hidden][name=old_contract_id]" ).val();
        var old_payment_type = $( "input[type=hidden][name=old_payment_type]" ).val();
        var old_payment_status = $( "input[type=hidden][name=old_payment_status]" ).val();
        var old_payment_note = $( "input[type=hidden][name=old_payment_note]" ).val();
        var old_time = $( "input[type=hidden][name=old_time]" ).val();
        var old_exported = $( "input[type=hidden][name=old_exported]" ).val();
        
        console.log(payment_status);
        
        // var result = false;
        var data_id = $(this).data('id');
        var aTag = $(this);
        
        var json = {
            user_id: user_id,
            contract_id: contract_id,
            payment_type: payment_type,
            payment_status: payment_status,
            payment_note: payment_note,
            time: time,
            exported: exported,
            old_contract_id: old_contract_id,
            old_payment_type: old_payment_type,
            old_payment_status: old_payment_status,
            old_payment_note: old_payment_note,
            old_time: old_time,
            old_exported: old_exported,
        };
        var jsonText = JSON.stringify(json);
        
        console.log('jsonText' + jsonText);
        
        // BootstrapDialog.confirm({
        //     title: 'Update export status',
        //     message: 'Do you really want to update this invoice\'s export status? Your action will be saved to history...',
        //     type: BootstrapDialog.TYPE_DANGER,
        //     callback: function (result) {
        //         if (result) {
                    $.ajax({
                        // url: base_url + '/update-invoice/' + invoice_id + '/' + user_id ,
                        url: base_url + '/update-exported-invoice/' + jsonText + '/' + invoice_id ,
                        method: "GET",
                        data: {
                            'invoice_id': invoice_id
                        },
                        async: true,
                    })
                    .done(function (data) {
                        if (data.result) {
                            console.log(invoice_id);
                            console.log('Untick ex done');
                            // location.reload();
                            if(data.expo == 'yes') {
                                console.log('yes');
                                // $(this).removeClass('btn-unpaid').addClass('btn-paid');
                                // aTag.replaceWith('<i class="tick_exported_invoice fas fa-check-square btn btn-exported" id="tick-invoice" data-id="' + data_id + '" ></i>');
                            }
                            if(data.expo == 'no') {
                                console.log('no');
                                // $(this).removeClass('btn-paid').addClass('btn-unpaid'); 
                                // aTag.replaceWith('<i class="tick_exported_invoice fas fa-check-square btn" id="tick-invoice" data-id="' + data_id + '"></i>');
                                
                            }
                        }
                    })
                    .fail(function (xhr, status, error) {
                        console.log('basic error');
                        if (xhr.status == 422) {
                            alert("Invoice is not rechecked yet")
                        } else {
                            console.log(this.url);
                            console.log(error);
                            console.log(xhr.responseText);
                        }
                        // result = false;
                    });
        //         }
        //     }
        // });
    });
}
