### Setup

 1. Download and extract the WHMCS Basic integration [here](https://install.synergycp.com/bm/integration/whmcs/synergycpbasic.zip)
 2. Copy the entire directory via FTP, SCP, etc. to `/<WHMCS_PATH>/modules/servers/synergycpbasic/`
 3. Go to SynergyCP Admin > System > Integrations.
 4. Add an Integration for WHMCS.
 5. Edit the Integration and make sure it has the following permissions:
     - Clients (View & Edit)
     - Installs (View & Edit)
     - Servers In Use (View & Edit)
     - Servers In Inventory (View & Edit)
     - IP Entities (View)
     - IP Groups (View)
 6. Create an API Key for the Integration, and copy the key.
 7. Go to WHMCS Admin > Setup (Top nav) > Products/Services > Servers
 8. Add New Server
     - Name: SynergyCP-Basic
     - Hostname: The hostname of the SynergyCP API - this should start with `api.`
 9. Scroll down to Server Details
     - Type: Synergy Control Panel - Basic
     - Access Hash: <API Key of SynergyCP Integration>

### Adding a New Product

1. Go to Setup (Top nav) > Products/Services > Products/Services > Create a New Product
2. Product Type: Dedicated/VPS Server
3. Product Name: The CPU name (e.g. E3-1270v6, Dual E5-2620v4)

To link the server in WHMCS with SynergyCP, add the WHMCS Integration and Billing ID to the server in SynergyCP under Hardware > Servers > Edit > Billing ID
