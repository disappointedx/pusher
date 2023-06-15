<template>
    <div class="containter mx-2 my-2">
      <div class="content my-2 mx-3">
        <div class="test_create">
          <div class="test_title text-center my-2">
            <label>Введите название теста</label>
          </div>
          <div class="row my-2">
            <div class="col ">
              <input type="text" class="form-control text-center" v-model="title">
            </div>
            <div class="col col-sm-4 text-center">
              <button class="btn btn-primary mx-2" @click="store">Создать</button>
              <button class = "btn btn-primary btn-test w-50 mx-2">Импортировать тест</button>
            </div>
          </div>
        </div>
        <div class="my_test my-5" v-for="test in tests" :key="test.id">
          <div class="row this_test my-3">
            <div class = "row" v-if="editTestId === test.id">
              <div class="col">
                <input type="text" class="form-control input_test" v-model="test.title">
              </div>
              <div class="col-4 buttons">
                <button class="btn btn-primary mx-1" @click="saveUpdate(test.id)">Сохранить</button>
                <button class="btn btn-secondary mx-1 my-1" @click="cancelUpdate">Отменить</button>
              </div>
            </div>
            <div class = 'row' v-else>
              <div class="col label_test">
                <button class = 'btn' @click = "GoToTest(test.id)">{{ test.title }}</button>
              </div>
              <div class="col-4 buttons">
                <button class="btn btn-primary" @click="startUpdate(test.id)">Изменить</button>
              </div>
              <div class="col-3 buttons">
                <button class="btn btn-danger" @click="this.delete(test.id)">Удалить</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: ['user_id', 'tests'],
    data() {
      return {
        title: '',
        editTestId: null,
      };
    },
    methods: {
      store() {
        axios
          .post('http://127.0.0.1:8000/test', {
            _method: 'POST',
            uid: this.user_id,
            title: this.title,
            question_quantity: 1
          })
          .then(response => {
            this.$toast.success('Тест создан');
            this.tests.unshift(response.data);
            this.title = '';
          })
          .catch(error => {
            this.$toast.error('Тест не создан');
          })
          .finally(() => {
            this.loading = false;
          });
      },
      startUpdate(testId) {
        this.editTestId = testId;
      },
      saveUpdate(testId) {
        const test = this.tests.find(test => test.id === testId);
        if (test) {
          axios
            .put(`http://127.0.0.1:8000/test/${testId}`, {
              _method: 'PUT',
              uid: this.user_id,
              title: test.title,
              question_quantity: test.question_quantity
            })
            .then(response => {
              this.$toast.success('Тест изменен');
              this.editTestId = null;
            })
            .catch(error => {
              this.$toast.error('Тест не изменен');
            })
            .finally(() => {
              this.loading = false;
            });
        }
      },
      cancelUpdate() {
        this.editTestId = null;
      },
      delete(count){
            axios.delete('http://127.0.0.1:8000/test/' + count)
        .then(response => {
            const index = this.tests.findIndex(test => test.id === count);
            if (index !== -1) {
                this.tests.splice(index, 1);
                this.$toast.success('Тест удален');
                this.$forceUpdate();
            }
        })
        .catch(error => {
            this.$toast.error('Тест не удален');
        });
        },
      GoToTest(count){
        const address = `http://127.0.0.1:8000/test/${count}`;
                // Изменить текущий URL на указанный маршрут
        window.location.href = address;
      }
    },
  };
  </script>
  
  <style>
  
  .buttons {
    text-align: center;
  }
  
  label {
    font-size: 20px;
  }
  
  .label_test {
    font-size: 16px;
    text-align: center;
  }
  
  .this_test {
    padding: 15px;
    background-color: rgb(255, 255, 255);
    -webkit-box-shadow: 5px 5px 5px -5px rgba(0, 0, 0, 0.6);
    -moz-box-shadow: 5px 5px 5px -5px rgba(0, 0, 0, 0.6);
    box-shadow: 5px 5px 5px -5px rgba(0, 0, 0, 0.6);
  }
  </style>