<template>
  <v-container
		class="mt-12 d-flex justify-center align-center"
	>
		<v-card
			elevation="24"
			:loading="loading"
			width="600"
			outlined
		>
			<template slot="progress">
				<v-progress-linear
					color="green"
					height="5"
					indeterminate
				></v-progress-linear>
			</template>

			<v-card-title class='d-flex justify-center'>Enter the postal code</v-card-title>

			<v-card-text>
				<v-row
					align='center'
					class='mt-3 d-flex justify-center'
				>
					<v-col>
						<v-form
							ref='form'
							v-model='valid'
							lazy-validation
						>
              <v-text-field
                v-model='postalCode.model'
                v-mask="'#####-###'"
                :rules='postalCode.rules'
                color='green'
                placeholder="#####-###"
                required='required'
                append-icon='mdi-card-search'
                outlined
              ></v-text-field>
						</v-form>
					</v-col>
				</v-row>
			</v-card-text>

			<v-card-actions class='mb-5 d-flex justify-center align-center'>
				<v-btn
					color='green'
					@click='validate()'
				>
          Search
				</v-btn>
			</v-card-actions>

      <v-scroll-y-transition mode="in-out" leave-absolute>
        <v-card
          v-if="reveal"
          elevation="24"
          outlined
          class="transition-fast-in-fast-out v-card--reveal"
        >
          <v-card-title class='d-flex justify-center'>Result</v-card-title>
          <v-card-text class="pb-0">
            <v-row>
              <v-col>
                <v-row justify="center">
                  <v-col xs sm="12" md="6" lg="6"><p><span>Postal Code: </span>{{ address.cep }}</p></v-col>
                  <v-col xs sm="12" md="6" lg="6"><p><span>Street: </span> {{ address.logradouro }}</p></v-col>
                </v-row>
                <v-row>
                  <v-col xs sm="12" md="6" lg="6"><p><span>Complement: </span> {{ address.complemento }}</p></v-col>
                  <v-col xs sm="12" md="6" lg="6"><p><span>Neighborhood: </span> {{ address.bairro }}</p></v-col>
                </v-row>
                <v-row>
                  <v-col xs sm="12" md="6" lg="6"><p><span>Location: </span> {{ address.localidade }}</p></v-col>
                  <v-col xs sm="12" md="6" lg="6"><p><span>State: </span> {{ address.uf }}</p></v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions class="pt-0">
            <v-btn
              text
              color="teal accent-4"
              @click="reveal = false"
            >
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-scroll-y-transition>

		</v-card>
	</v-container>
</template>

<script>
export default {
  name: 'IndexPage',
  data: () => ({
      address: null,
      reveal: false,
			loading: false,
      valid: true,
      postalCode: {
        model: '',
        rules: [
          v => !!v || 'Postal Code is required',
          v => /^[0-9]{5}-[0-9]{3}$/.test(v) || 'Invalid Postal Code'
        ]
      },
		}),
		methods: {
      validate () {
				if (this.$refs.form.validate()) {
        	this.search();
				}
			},
			async search () {
        this.loading = true;
        const serachUrl = `https://viacep.com.br/ws`;
        const url = `${serachUrl}/${this.postalCode.model.replace('-','')}/json/`;
        await this.$axios.$get(url)
          .then((response) => {
            if (response?.erro) {
              this.$toast.open({
                message: 'Could not find address...',
                type: 'warning'
              });
            } else {
              this.address = response;
              this.reveal = true;
            }
          }).catch((error) => {
            this.$toast.open({
                message: error,
                type: 'error'
              });
          }).finally(() => {
            this.loading = false;
          })
			}
		}
}
</script>

<style scoped>
  .v-card--reveal {
    bottom: 0%;
    opacity: 1 !important;
    position: absolute;
    width: 100%;
  }
  p {
    font-size: 15px;
  }
  span {
    font-weight: bold;
  }
  </style>
