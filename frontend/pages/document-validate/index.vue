<template>
  <v-container
		class="mt-12 d-flex justify-center align-center"
	>
		<v-card
			elevation="24"
			width="600"
			outlined
		>
			<v-card-title class='d-flex justify-center'>Validate CPF or CNPJ</v-card-title>

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
                v-model='cpf.model'
                v-mask="'###.###.###-##'"
                :rules="cpf.rules"
                color='green'
                placeholder="CPF"
                required='required'
                outlined
              ></v-text-field>

              <v-text-field
                v-model='cnpj.model'
                v-mask="'##.###.###/####-##'"
                :rules="cnpj.rules"
                color='green'
                placeholder="CNPJ"
                required='required'
                outlined
              ></v-text-field>
						</v-form>
					</v-col>
				</v-row>
			</v-card-text>
		</v-card>
	</v-container>
</template>

<script>
  export default {
    data: () => ({
      mounted: false,
      cpf: {
        model: '',
        rules: []
      },
      cnpj: {
        model: '',
        rules: []
      },
      valid: true,
    }),
    mounted() {
      this.cpf.rules.push(v => this.validateCPF(v) || 'Invalid CPF document')
      this.cnpj.rules.push(v => this.validateCNPJ(v) || 'Invalid CNPJ document')
    },
    methods: {
      validateCPF(cpf) {
        if (!cpf || typeof cpf === 'undefined') return
        let soma = 0
        let resto

        const strCPF = cpf.replace(/[^\d]/g, '')
        
        if (strCPF.length !== 11) return false
        
        if ([
          '00000000000',
          '11111111111',
          '22222222222',
          '33333333333',
          '44444444444',
          '55555555555',
          '66666666666',
          '77777777777',
          '88888888888',
          '99999999999',
          ].includes(strCPF)) return false

        for (let i = 1; i <= 9; i++) {
          soma = soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
        }

        resto = (soma * 10) % 11

        if ((resto === 10) || (resto === 11)) resto = 0

        if (resto !== parseInt(strCPF.substring(9, 10)) ) return false

        soma = 0

        for (let i = 1; i <= 10; i++) {
          soma = soma + parseInt(strCPF.substring(i-1, i)) * (12 - i)
        }

        resto = (soma * 10) % 11

        if ((resto === 10) || (resto === 11)) resto = 0

        if (resto !== parseInt(strCPF.substring(10, 11) ) ) return false

        return true
      },

      validateCNPJ (cnpj) {
        if (!cnpj || typeof cnpj === 'undefined') return
        const b = [ 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2 ]
        const c = cnpj.replace(/[^\d]/g, '')
        
        if(c.length !== 14) return false

        if(/0{14}/.test(c)) return false

        // eslint-disable-next-line
        for (var i = 0, n = 0; i < 12; n += parseInt(c[i]) * b[++i]) {
          console.log(c[i])
          // eslint-disable-next-line
          if(parseInt(c[12]) != (((n %= 11) < 2) ? 0 : 11 - n)) return false
        }
        

         // eslint-disable-next-line
        for (var i = 0, n = 0; i <= 12; n += c[i] * b[i++]) {
          // eslint-disable-next-line
          if(parseInt(c[13]) != (((n %= 11) < 2) ? 0 : 11 - n)) return false
        }

        return true
    }
    }
  }
</script>

<style scoped>
  .v-card__text {
    overflow: auto;
  }
</style>