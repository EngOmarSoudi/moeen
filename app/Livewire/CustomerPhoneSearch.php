<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;

class CustomerPhoneSearch extends Component
{
    public $phoneNumber = '';
    public $selectedCustomerId = null;
    public $customerDetails = null;
    public $showCreateForm = false;
    public $searchPerformed = false;

    public function updatedPhoneNumber()
    {
        $this->searchPerformed = false;
        $this->customerDetails = null;
        $this->showCreateForm = false;
        $this->selectedCustomerId = null;

        // Clear if empty
        if (empty($this->phoneNumber)) {
            return;
        }

        // Search for customer by phone
        $customer = Customer::where('phone', $this->phoneNumber)->first();

        if ($customer) {
            $this->customerDetails = [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'nationality' => $customer->nationality,
                'document_type' => $customer->document_type,
                'document_no' => $customer->document_no,
                'issuing_authority' => $customer->issuing_authority,
                'status_id' => $customer->status_id,
                'agent_id' => $customer->agent_id,
            ];
            $this->selectedCustomerId = $customer->id;
            $this->showCreateForm = false;
        } else {
            $this->showCreateForm = true;
        }

        $this->searchPerformed = true;
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->dispatch('customer-selected', customerId: $customerId);
    }

    public function render()
    {
        return view('livewire.customer-phone-search');
    }
}
