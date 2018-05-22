<?php
class ControllerExtensionPaymentHipsCheckout extends Controller
{
    private $error = array();
    
    public function index()
    {
        $this->load->language('extension/payment/hips_checkout');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('hips', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
        }
        
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        
        $data['entry_key']          = $this->language->get('entry_key');
        $data['button_save']        = $this->language->get('save');
        $data['help_extended_cart'] = $this->language->get('help_extended_cart');
        $data['help_total']         = $this->language->get('help_total');
        
        $data['button_cancel']      = $this->language->get('cancel');
        $data['public_entry_key']   = $this->language->get('public_entry_key');
        $data['entry_mode_bar']     = $this->language->get('entry_mode_bar');
        $data['entry_payment_type'] = $this->language->get('entry_payment_type');
        $data['entry_total']        = $this->language->get('entry_total');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_terms']        = $this->language->get('entry_terms');
        $data['entry_geo_zone']     = $this->language->get('entry_geo_zone');
        $data['entry_status']       = $this->language->get('entry_status');
        $data['entry_sort_order']   = $this->language->get('entry_sort_order');
        
        $data['text_edit']      = $this->language->get('text_edit');
        $data['text_enabled']   = $this->language->get('text_enabled');
        $data['text_disabled']  = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');        
        
        $data['token'] = $this->session->data['token'];
        
        if (isset($this->request->post['hips_geo_zone_id'])) {
            $data['hips_geo_zone_id'] = $this->request->post['hips_geo_zone_id'];
        } else {
            $data['hips_geo_zone_id'] = $this->config->get('hips_geo_zone_id');
        }
        
        $this->load->model('localisation/geo_zone');
        
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        
        
        if (isset($this->request->post['hips_key'])) {
            $data['hips_key'] = $this->request->post['hips_key'];
        } else {
            $data['hips_key'] = $this->config->get('hips_key');
        }
        
        if (isset($this->request->post['hips_key_public'])) {
            $data['hips_key_public'] = $this->request->post['hips_key_public'];
        } else {
            $data['hips_key_public'] = $this->config->get('hips_key_public');
        }
        
        if (isset($this->request->post['hips_mode_bar'])) {
            $data['hips_mode_bar'] = $this->request->post['hips_mode_bar'];
        } else {
            $data['hips_mode_bar'] = $this->config->get('hips_mode_bar');
        }
        
        if (isset($this->request->post['hips_mode'])) {
            $data['hips_mode'] = $this->request->post['hips_mode'];
        } else {
            $data['hips_mode'] = $this->config->get('hips_mode');
        }
        
        if (isset($this->request->post['hips_method'])) {
            $data['hips_method'] = $this->request->post['hips_method'];
        } else {
            $data['hips_method'] = $this->config->get('hips_method');
        }
        
        if (isset($this->request->post['hips_payment_type'])) {
            $data['hips_payment_type'] = $this->request->post['hips_payment_type'];
        } else {
            $data['hips_payment_type'] = $this->config->get('hips_payment_type');
        }
        
        if (isset($this->request->post['hips_total'])) {
            $data['hips_total'] = $this->request->post['hips_total'];
        } else {
            $data['hips_total'] = $this->config->get('hips_total');
        }
        
        if (isset($this->request->post['hips_order_status_id'])) {
            $data['hips_order_status_id'] = $this->request->post['hips_order_status_id'];
        } else {
            $data['hips_order_status_id'] = $this->config->get('hips_order_status_id');
        }
        
        if (isset($this->request->post['hips_checkout_status'])) {
            $data['hips_checkout_status'] = $this->request->post['hips_checkout_status'];
        } else {
            $data['hips_checkout_status'] = $this->config->get('hips_checkout_status');
        }
        
        if (isset($this->request->post['hips_checkout_sort_order'])) {
            $data['hips_checkout_sort_order'] = $this->request->post['hips_checkout_sort_order'];
        } else {
            $data['hips_checkout_sort_order'] = $this->config->get('hips_checkout_sort_order');
        }
        
        if (isset($this->error['key'])) {
            $data['error_key'] = $this->language->get('Private_key_error');
        } else {
            $data['error_key'] = '';
        }
        
        
        if (isset($this->error['key_public'])) {
            $data['error_key_public'] = $this->language->get('Public_key_error');
        } else {
            $data['error_key_public'] = '';
        }
        
        $this->load->model('catalog/information');
        
        $data['informations'] = $this->model_catalog_information->getInformations();
        
        $this->load->model('localisation/currency');
        
        $data['currencies'] = $this->model_localisation_currency->getCurrencies();
        
        $this->load->model('localisation/order_status');
        
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        $this->load->model('extension/payment/hips_checkout');
        
        if ($this->model_extension_payment_hips_checkout->checkForPaymentTaxes()) {
            $data['error_tax_warning'] = $this->language->get('error_tax_warning');
        } else {
            $data['error_tax_warning'] = '';
        }
        
        if (isset($this->error['account_warning'])) {
            $data['error_account_warning'] = $this->error['account_warning'];
        } else {
            $data['error_account_warning'] = '';
        }
        
        if (isset($this->error['account'])) {
            $data['error_account'] = $this->error['account'];
        } else {
            $data['error_account'] = array();
        }
        
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/hips_checkout', 'token=' . $this->session->data['token'], true)
        );
        
        $data['action'] = $this->url->link('extension/payment/hips_checkout', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
        
        
        if (isset($this->request->post['hips_checkout_total'])) {
            $data['hips_checkout_total'] = $this->request->post['hips_checkout_total'];
        } else {
            $data['hips_checkout_total'] = $this->config->get('hips_checkout_total');
        }
        
        
        if (isset($this->request->post['hips_checkout_terms'])) {
            $data['hips_checkout_terms'] = $this->request->post['hips_checkout_terms'];
        } else {
            $data['hips_checkout_terms'] = $this->config->get('hips_checkout_terms');
        }
        
        
        $data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;
        
        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/payment/hips_checkout', $data));
    }
    
    
    public function install()
    {
        $this->load->model('extension/payment/hips_checkout');
        $this->model_extension_payment_hips_checkout->install();
    }
    
    public function uninstall()
    {
        $this->load->model('extension/payment/hips_checkout');
        $this->model_extension_payment_hips_checkout->uninstall();
    }
    
    
    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/hips_checkout')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        
        if (!$this->request->post['hips_key']) {
            $this->error['key'] = $this->language->get('error_key');
        }
        
        if (!$this->request->post['hips_key_public']) {
            $this->error['key_public'] = $this->language->get('Public_key_error');
        }
        
        return !$this->error;
    }
} 