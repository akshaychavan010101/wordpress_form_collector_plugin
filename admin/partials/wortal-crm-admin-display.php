<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
  die('Un-authorized access!');
}


// Render the admin page
function render_wortal_admin_page()
{
?>
  <div id="wortal-page" @vue:mounted="onMounted()">
    <div class="max-w-984 mx-auto bg-white text-dark  py-16 p-8 mt-8 flex text-start">
      <div class="max-w-2xl mx-auto">
        <div>
          <div class="flex justify-start items-center w-100 relative">
            <div class="flex justify-start items-center  " style="max-width: 150px">
              <img class="" :src="logoUrls?.wortalLogo" />
            </div>
            <div class="absolute right-82">
              <img class="" :src="logoUrls?.linkLogo" />
            </div>
            <div class="flex justify-start items-center  " style="max-width: 150px">
              <img class="" :src="logoUrls?.wordpressLogo" />
            </div>
          </div>
        </div>

        <h1 class="text-xxl font-bold text-dark mb-2 mt-4 truncate">Get Leads to Wortal CRM</h1>
        <p class="mb-12 text-base">
          Connect your WordPress website to Wortal CRM to automatically get leads from your contact forms to your wortal account.
        </p>

        <!-- Token Field -->
        <div class="text-left mb-6">
          <div class="relative">
            <label class="inline absolute text-gray-700 text-sm bg-white  p-lr-5  left-3 -top-5" for="wortal_key">
              WORTAL KEY <span style="color:red">*</span>
            </label>
            <input id="wortal_key" v-model="wortalKey" class="wortal-text-input rounded" :class="getTokenClasses()" type="text" placeholder="WP_kxfSTVPy_25" @keydown="isFormDirty = true" />
          </div>
          <div class="text-xs text-red-700 tracking-wider font-normal mt-2" v-if="wortalKeyError">
            {{ wortalKeyError }}
          </div>
          <p class="my-1 text-gray-500">
            To get your wortal key from your <strong>Wortal Integrations Page
              <a :href="wortalIntegrationPageLink" target="_blank" class="wortal-external-link">
                Click here
              </a>
            </strong>
          </p>
        </div>

        <!-- Website Name Field -->
        <div class="text-left mb-6" v-if="isIntegrated">
          <div class="relative">
            <label class="inline absolute text-gray-700 text-sm bg-white  p-lr-5  left-3 -top-5" for="web_reference">
              WEBSITE NAME
            </label>
            <input id="web_reference"  v-model="webReference" class="wortal-text-input rounded border-gray-500" type="text" placeholder="e.g. myweb.com" @keydown="isFormDirty = true" />
            <p class="my-1 text-gray-500">
              This Website Name field will be displayed on your new lead details.
            </p>
          </div>
        </div>

        <div class="text-left mb-4">
          <label class="block text-gray-700 text-sm font-semibold mb-2">
            Supported Forms
          </label>
          <p v-if="isIntegrated" class="my-1">
            Select the WordPress contact form(s) you're using to connect them.
            Once connected, you can submit a test lead on your contact form to confirm.
          </p>
          <p v-else class="my-1">
            Wortal supports below WordPress contact form plugins/themes.
            Just enter your <strong>Wortal Key</strong> above and hit below <strong>SAVE</strong> button to connect your installed contact form/theme.
          </p>
        </div>


        <div class="text-left" v-if="!isIntegrated">
          <span v-for="(integration, index) in integrationsDownloaded" :key="index" class="border border-2 inline-block px-3 py-1 rounded font-semibold mr-2 mb-2 uppercase" :style="{ background: integration.bgColor, color: integration.borderColor, borderColor:  integration.borderColor }">
            <img class="inline float-left mr-2" style="max-height: 16px" :src="integration.iconUrl" />
            <span></span>{{ integration.name }}
          </span>
        </div>

        <!-- List of integration toggle boxes -->
        <div class="text-left" v-else>
          <div v-for="(integration, index) in allowedIntegrations" class="border mb-4 p-4 rounded" :class="getFieldClasses(integration)" :style="getStyle(integration)">
            <label class="wortal-integration-header flex" :for="integration.key">
              <img class="inline float-left mr-2" style="max-width: 16px" :src="integration.iconUrl" />
              <span class="flex-1">
                {{ integration.name }}
              </span>
              <label class="connected mr-4" v-if="integration.status === Status.Connected && integration.enabled">
                Connected!
              </label>
              <label class="text-sky-600 font-bold mr-4" v-else-if="integration.status === Status.Activated && integration.enabled">
                Save to Confirm
              </label>
              <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in self-end">
                <input v-model="integration.enabled" @change="handleOnToggleChange(index)" type="checkbox" name="toggle" :id="integration.key" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white appearance-none cursor-pointer" />
                <label :for="integration.key" class="toggle-label block overflow-hidden w-8 h-4 rounded-full bg-gray-300 cursor-pointer"></label>
              </div>
            </label>
            <div class="text-xs text-red-700 tracking-wider font-normal mt-2" v-if="integration.errorMessage">
              {{ integration.errorMessage }}
            </div>
          </div>
        </div>

        <button class="btn w-1/2 btn_save  rounded" :class="isIntegrated ? 'mt-3' : 'mt-8'" :disabled="!isFormDirty || isSubmitting" @click="handleSubmit">
          <img class="inline mr-2" style="max-height: 14px; min-height: 12px" :src="logoUrls?.saveVector" />
          {{isSubmitting ? 'Saving...' : 'Save'}}
        </button>
      </div>
    </div>

    <!-- Help Footer Text -->
    <!-- <p class="text-center text-gray-400 text-sm mt-4">
      Check out our help guide for more details on
      <a href="https://help.wortal.co" class="wortal-external-link" target="_blank">
        connecting WordPress to wortal
      </a>
    </p>
    <p class="text-center text-gray-400 text-sm mt-4">
      Need help?
      <a href="mailto:support@wortal.com" class="wortal-external-link">
        Contact wortal Support
      </a>
    </p> -->

    <!-- Toast -->
    <div class="flex justify-center fixed bottom-7 z-100" style="width: -webkit-fill-available">
      <div v-show="toast.visible" class="shadow-lg px-6 py-2 rounded flex items-center justify-center" :class="getToastStyles()">
        <div class="toast-icon mr-4"></div>
        <div class="flex flex-col text-sm tracking-wide mr-6">
          <div class="font-medium">{{ toast.heading }}</div>
          <div>{{ toast.message }}</div>
        </div>
        <button class="ml-auto px-3 py-2 rounded focus:outline-none" @click="toast.visible = false">
          <div class="toast-close-icon"></div>
        </button>
      </div>
    </div>
  </div>

  <script>
    <?php
    $wortal_config = Wortal_CRM_Options::get_values();
    $logo_urls = Wortal_CRM_Constants::get_logo_url();
    $data_from_wortal_wp = array(
      "wordpressAjaxUrl" => esc_url(admin_url('admin-ajax.php')),
      "wortalWebhookSubscriptionUrl" =>  Wortal_CRM_Constants::get_webhook_integration_url(),
      "wortalIntegrationPageLink" => Wortal_CRM_Constants::WEBAPP_INTEGRATION_PAGE_LINK,
      "formActionName" => Wortal_CRM_Constants::WP_SAVE_HOOK_NAME,
      "logoUrls" => $logo_urls,
      "allowedIntegrations" => Wortal_CRM_Options::get_allowed_integrations(),
      "wortalKey" => isset($wortal_config['wortal_key']) ? $wortal_config['wortal_key'] : '',
      "webReference" => isset($wortal_config['web_reference']) ? $wortal_config['web_reference'] : '',
      "Status" => Wortal_CRM_Integration_Status::to_array()
    );
    ?>

    const dataFromWortalWP = <?php echo wp_json_encode($data_from_wortal_wp, JSON_HEX_TAG); ?>;


    PetiteVue.createApp({
      ...dataFromWortalWP,
      isSubmitting: false,
      isIntegrated: !!dataFromWortalWP.wortalKey,
      wortalKeyError: null,
      isFormDirty: false,
      toast: {
        visible: false,
        type: '',
        heading: '',
        message: ''
      },
      integrationsDownloaded: dataFromWortalWP.allowedIntegrations,

      onMounted() {
        const url = new URL(window.location.href)
        if (!url.searchParams.has('success')) return
        this.showToast({
          type: 'success',
          heading: 'Saved',
          message: 'Success! Your updates have been saved'
        })
      },

      getFieldClasses(integration) {
        if (integration.enabled) {
          if (integration.status === dataFromWortalWP.Status.Connected) {
            return 'border-green-400 bg-green-100'
          }
          if (integration.status === dataFromWortalWP.Status.Activated) {
            return 'border-sky-400 bg-sky-100'
          }
        }
        return 'border-gray-200 bg-gray-50'
      },

      getStyle(integration) {
        if (integration.enabled) {
          if (integration.status === dataFromWortalWP.Status.Connected) {
            return {
              borderColor: integration.borderColor,
              backgroundColor: integration.bgColor,
            }
          }
        }
        return {
          borderColor: 'gray-500',
          backgroundColor: 'gray-500',
        }
      },

      getInputClasses(integration) {
        if (integration.status === dataFromWortalWP.Status.Connected) return 'border-green-400 connected'
        return 'border-gray-500'
      },

      getTokenClasses() {
        if (this.wortalKeyError) return 'border-red-400 text-red-700 error'
        return 'border-gray-500'
      },

      getToastStyles() {
        const styles = {
          error: 'toast-error bg-fire-light text-fire',
          success: 'toast-success bg-secondary-light text-secondary'
        }
        return styles[this.toast.type]
      },

      clearAllErrors() {
        this.wortalKeyError = null
        this.allowedIntegrations.forEach((_, index) => {
          this.allowedIntegrations[index].errorMessage = null
        })
      },

      getPayload() {
        return this.allowedIntegrations
          .filter(integration => integration.status !== this.Status.NotExist)
          .filter(integration => integration.enabled)
          .reduce((prev, integration) => ({
            ...prev,
            [integration.key]: integration.enabled
          }), {
            wortal_key: this.wortalKey
          })

      },

      integrateWortalKey() {
        const payload = this.getPayload()
        return fetch(this.wortalWebhookSubscriptionUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
          })
          .then(response => response.json())
          .then((result) => {
            if (result.success && result.success !== "False") {
              return result
            }
            this.wortalKeyError = result.message
            const error = new Error('Oops! The wortal key is not valid. Please check and try again.')
            error.heading = 'Invalid wortal key'
            throw error
          })
      },

      saveFormIntoWP() {
        const payload = this.getPayload()
        return fetch(this.wordpressAjaxUrl, {
          method: 'POST',
          body: new URLSearchParams({
            ...payload,
            action: this.formActionName,
            web_reference: this.webReference
          })
        })
      },

      handleOnToggleChange(index) {
        const status = this.allowedIntegrations[index].status
        const errorMessageMap = {
          [this.Status.NotExist]: "Please install the plugin or theme on your WordPress site to enable this integration.",
          [this.Status.Installed]: "Please activate the plugin or theme on your WordPress site to enable this integration.",
        }
        const errorMessage = errorMessageMap[status]
        if (!errorMessage) {
          this.isFormDirty = true
          return
        }
        this.allowedIntegrations[index].errorMessage = errorMessage

        const delay = 200
        setTimeout(() => {
          this.allowedIntegrations[index].enabled = false
        }, delay)
      },

      websiteValidation(value){
        const regex =  /^((https?:\/\/)?(wwww\.)?[a-zA-Z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?)?$/
        return regex.test(value)
      },

      handleSubmit() {
        const websiteName = this.webReference?.trim()
        if (this.isIntegrated && websiteName && !this.websiteValidation(websiteName)) {
          this.showToast({
            type: 'error',
            heading: 'Invalid Website Name',
            message: 'Please enter a valid website name.'
          })
          return
        }
        this.clearAllErrors();
        this.isSubmitting = true
        this.integrateWortalKey()
          .then(this.saveFormIntoWP)
          .then(() => {
            window.location.search += "&success=true";
          })
          .catch((error) => {
            this.isSubmitting = false
            this.showToast({
              type: 'error',
              heading: error.heading,
              message: error.message.includes('not valid') ? error.message : null
            })
          })
      },

      showToast(params) {
        this.toast.visible = true
        this.toast.type = params.type
        this.toast.heading = params.heading || 'Errors Detected'
        this.toast.message = params.message || 'There were one or more errors detected. Please check and try again.'

        const delay = 3000
        setTimeout(() => {
          this.toast.visible = false
        }, delay)
      }
    }).mount("#wortal-page")
  </script>
<?php
}
