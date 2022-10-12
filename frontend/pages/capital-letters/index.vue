<template>
  <v-container
		class="mt-12 d-flex justify-center align-center"
	>
		<v-card
			elevation="24"
			width="600"
			outlined
		>
			<v-card-title v-if="!small" class='d-flex justify-center'>Capture all capital letters you type</v-card-title>
      <v-card-title v-else class='d-flex justify-center'>Capture all small letters you type</v-card-title>

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
                v-model='input'
                color='green'
                placeholder="Type something"
                required='required'
                :append-icon="small ? 'mdi-alphabetical' : 'mdi-alphabetical-variant'"
                outlined
                @click:append="() => (small = !small)"
              ></v-text-field>
						</v-form>
					</v-col>
				</v-row>
			</v-card-text>
      <v-row>
        <v-col class='mb-5 d-flex justify-center'>
          <v-card
            width="550"
            outlined
          >
            <v-card-text>
              <span>{{ letters }}</span>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
		</v-card>
	</v-container>
</template>

<script>
  export default {
    data: () => ({
      letters: '',
      valid: true,
      small: false,
      input: '',
    }),
    watch: {
      input: function(newVal, oldVal) {
        let regxp = /[^A-Z]/g;
        if (this.small) {
          regxp = /[^a-z]/g
        }
        const letters = newVal.replace(regxp, '');
        this.letters = letters;
      },
      small: function(newVal, oldVal) {
        this.letters = '';
        this.input = '';
      },
    }
  }
</script>

<style scoped>
  .v-card__text {
    overflow: auto;
  }
</style>