<template>
    <div class="container">
        <div class="content">
            <div class="center">
                <label>Введите PIN</label>
                <p align="center"><input type="text" class="form-control" v-model="room_pin" placeholder="Введите PIN комнаты"></p>
                <label>Введите имя</label>
                <p align="center"><input type="text" class="form-control" v-model="name" placeholder="Введите свое имя"></p>
                <button class="btn btn-primary my-2" @click="store()">Войти</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {},
    data() {
        return {
            room_pin: '',
            name: ''
        };
    },
    methods: {
        store() {
            axios
                .post('http://10.3.3.18/open_session', {
                    _method: 'POST',
                    room_pin: this.room_pin,
                    name: this.name,
                    score: 0
                })
                .then(response => {
                    this.$toast.success('Сессия открыта');
                    this.GoToRoom(this.room_pin);
                })
                .catch(error => {
                    this.$toast.error(this.error);
                });
        },
        GoToRoom(count) {
            const address = `http://10.3.3.18/room/${count}`;
            window.location.href = address;
        }
    }
};
</script>

<style>
.center {
    padding: 50px;
    margin: 10% auto;
    text-align: center;
    font-weight: 500;
    background-color: white;
    max-width: 600px;
    width: 90%;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-control {
    box-sizing: border-box;
    border: 1px solid rgb(0, 0, 0);
    border-radius: 4px;
}

.inputs {
    border: 1px solid rgb(0, 0, 0);
    border-radius: 4px;
}

.input {
    padding: 10px;
    text-align: center;
}

.content {
    padding-top: 5px;
    padding-left: 5px;
}

.btn {
    width: 150px;
}
</style>
