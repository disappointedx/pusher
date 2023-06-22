<template>
    <div class="container">
      <div class="create my-5">
            <h2 class="text-center my-3">Создание комнаты</h2>
            <div class="form-group">
                <label for="title">Название комнаты:</label>
                <input type="text" class="form-control" id="title" v-model="title" placeholder="Введите название комнаты">
            </div>
            <div class="form-group my-2">
                <label for="test">Выберите тест:</label>
                <select class="form-control" id="test" v-model="test_id">
                <option value="-1" disabled selected>Выберите тест</option>
                <option v-for="test in tests" :value="test.id">{{ test.title }}</option>
                </select>
            </div>
        <div class="text-center button_create">
            <button class="btn btn-primary" @click="store">Создать комнату</button>
        </div>
      </div>

      <div class="active">
        <h2 class="text-center my-5">Активные комнаты</h2>
      <div v-if="this.activeRooms.length === 0" class="text-center">Нет активных комнат</div>
      <div class="row" v-else>
        <div class="col-md-4" v-for="room in this.rooms" :key="room.id">
          <div class="card room_item">
            <div class="card-body">
              <h5 class="card-title">{{ room.pin }}</h5>
              <p class="card-text">
                {{ room.status_game === 1 ? 'Ожидание игроков' : room.status_game === 2 ? 'Идет игра' : room.status_game === 0 ? 'Игра в ожидании пользователей' : 'Игра закончена' }}
              </p>
              <button class="btn btn-primary enter_button" @click="GoToRoute(room.pin)">Войти</button>
            </div>
          </div>
        </div>
      </div>
      </div>

    </div>
  </template>
  
  <script>
  export default {
    props: ['user_id', 'rooms', 'tests'],
    data() {
      return {
        title: '',
        test_id: -1,
      };
    },
    computed: {
        activeRooms() {
        return this.rooms.filter(room => room.status_game !== 3);
      },
    },
    created() {
        const self = this;
        window.Echo.channel('store_room').listen('.store_room', response => {
            console.log(response);
            self.rooms.unshift(response.room);
            self.$forceUpdate();
        });
        window.Echo.channel('update_room').listen('.update_room', response => {
            const roomId = response.room.id;
            const index = self.rooms.findIndex(room => room.id === roomId);
            if (index !== -1) {
                self.rooms.splice(index, 1, response.room);
                this.activeRooms();
                self.$forceUpdate();
            }
        });
    },
    methods: {
      store() {
        if (this.test_id === -1) {
          alert('Вы не выбрали тест');
        } else {
          axios
            .post('http://10.2.9.27/room', {
              _method: 'POST',
              pin: this.title,
              room_owner: this.user_id,
              status_game: 0,
              question_current: 0,
              test_id: this.test_id
            })
            .then(response => {
              this.title = '';
              this.rooms.unshift(response.data);
              console.log(response.data);
              this.$forceUpdate();
              this.$toast.success('Комната создана');
              this.$nextTick(() => {
              this.activeRooms.unshift(response.data);
              });
            })
            .catch(error => {
                this.$toast.success('Комната не создана');
            });
        }
      },
      GoToRoute(item) {
        const address = `http://10.2.9.27/room/${item}`;
        window.open(address);
      },
    },
  };
  </script>
  
  <style>
  .button_create {
    margin-top: 2rem;
  }
  
  .room_item {
    margin-top: 1rem;
  }
  
  .enter_button {
    margin-top: 0.5rem;
  }
  .create{
    padding: 20px;
    background: #ffffff;
    -webkit-box-shadow: 5px 5px 5px -5px rgba(34, 60, 80, 0.6);
-moz-box-shadow: 5px 5px 5px -5px rgba(34, 60, 80, 0.6);
box-shadow: 5px 5px 5px -5px rgba(34, 60, 80, 0.6);
  }
  .active {
    padding: 20px;
    background: #ffffff;
    -webkit-box-shadow: 5px 5px 5px -5px rgba(34, 60, 80, 0.6);
-moz-box-shadow: 5px 5px 5px -5px rgba(34, 60, 80, 0.6);
box-shadow: 5px 5px 5px -5px rgba(34, 60, 80, 0.6);
  }
  .bottom {
    border-bottom: 1px solid black;
    border-radius: 10px;
  }


  </style>
  