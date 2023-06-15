<template>
    <div class="container">
        <div class="content">

            <!-- Это игра еще не начата -->
            <div v-if="room[0].status_game === 0">
                <div v-if="user_id === room[0].room_owner">
                    <div class="my-3 text-center fs-2">Вы являетесь создателем комнаты.</div>
                    <div class="room_pin my-2">
                        <h1>PIN этой комнаты: {{ room[0].pin }}</h1>
                    </div>
                    <div class="text-center fs-3 my-5">
                        Присоединившиеся пользователи
                    </div>
                    <div v-for="user in game_user" :key="user.id" class="users">{{ user.name }}</div>
                    <div class="buttons my-2">
                        <button class="btn btn-primary" @click.prevent="StartGame(room[0].id)">Начать викторину</button>
                    </div>
                </div>
                <div v-else>
                    <div class="text-center fs-2">
                        Ожидание других пользователей
                    </div>
                </div>
            </div>


            <!-- Это игра идет -->
            <div v-if="room[0].status_game === 1">
                <div v-if="user_id === room[0].room_owner">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped" role="progressbar"
                            :style="{ width: ((this.questionTimer / this.questions[room[0].question_current].timer)*100) + '%' }"
                            aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-center">
                    <h1>{{ questions[room[0].question_current].title }}</h1>
                    </p>
                    <div class="justify-content-between align-items-between">
                        <a class="" v-for="(answer, index) in filteredAnswers" :key="answer.id" @click.prevent>
                            <div :class="'flex blocks fs-3 btn'+index" @click.prevent>{{ answer.title }}</div>
                        </a>
                    </div>
                </div>
                <div v-else>
                    <div v-if="status_answer==0">
                        {{ handleStatusGameChange() }}
                        <span class="" v-for="(answer, index) in filteredAnswers" :key="answer.id">
                            <button @click=SetUserAnswer(answer.id) :class="' flex blocks p-5 btne fs-2 btn'+index">
                                Вариант {{ index + 1 }}</button>
                        </span>
                    </div>
                    <div v-else>
                        <div class="text-center">
                            <img src="https://thumbs.gfycat.com/BruisedIndolentCrustacean-size_restricted.gif">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Эта промежуточные результаты  -->
            <div v-if="room[0].status_game === 2">
                <div class="users_green">
                    <div class="row">
                        <div class="col text-center">Пользователь</div>
                        <div class="col text-center">Очки</div>
                    </div>
                </div>
                <div v-for="user in game_user" :key="user.id">
                    <div v-if="questions.length !== room[0].question_current + 1">
                        <div v-if="user_id == room[0].room_owner">
                            <div class="users">
                                <div class="row">
                                    <div class="col text-center">{{ user.name }}</div>
                                    <div class="col text-center">{{ user.score }}</div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            hello
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="user_id === room[0].room_owner">
                            <div v-for="user in game_user">
                                <div class="users">
                                    <h3>{{ user.name }} {{ user.score }}</h3>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <h1>Игра закончена</h1>
                        </div>
                    </div>
                </div>
                <div v-if="user_id === room[0].room_owner">
                    <div v-if="room[0].question_current==questions.length - 1">
                        <div class="text-center my-2">
                            <button @click.prevent="GameEnd(room[0].id)" class="btn btn-primary"> Закончить </button>
                        </div>
                    </div>
                    <div v-else>
                        <div class="text-center my-2">
                            <button @click.prevent="GameContinue(room[0].id)" class="btn btn-primary"> Продолжить
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div v-if="status_answer == 1">
                        <p align="center"><img src="https://cdn-icons-png.flaticon.com/512/148/148767.png"
                                class="status_answer_logo"></p>
                        <div class="text-center fs-3">
                            Вы ответили правильно
                        </div>
                    </div>
                    <div v-if="status_answer == -1">
                        <p align="center"><img src="https://www.freeiconspng.com/thumbs/error-icon/error-icon-4.png"
                                class="status_answer_logo"></p>
                        <div class="text-center fs-3">
                            Вы ответили неправильно
                        </div>
                    </div>
                </div>
            </div>

            <!-- Это игра закончена -->
            <div v-if="room[0].status_game == 3">

            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: ['room', 'game_user', 'user_id', 'session', 'questions', 'answers'],
    data() {
        return {
            questionTimer: 1,
            intervalId: null,
            useranswer: -1,
            proc: "10%",
            filteredAnswers: [], // Добавил пустой массив для отфильтрованных ответов
            status_answer: 0,
            fill: {
                gradient: ["red", "green", "blue"]
            },
        };
    },
    watch: {
        // Watching for changes in the questionTimer property
        questionTimer(newValue) {
            const expiryDate = new Date();
            expiryDate.setHours(expiryDate.getHours() + 1);
            // Setting the value of questionTimer in a cookie
            document.cookie = `questionTimer=${newValue}; expires=${expiryDate.toUTCString()}; path=/`;
        },
        filteredAnswers(newValue) {
            this.setCookie('filteredAnswers', JSON.stringify(newValue));
        },
    },
    created() {
        const questionTimerCookie = this.getCookie('questionTimer');
        this.questionTimer = questionTimerCookie ? parseInt(questionTimerCookie) : this.questions[this.room[0]
            .question_current].timer;

        if (this.questionTimer === 0) {
            this.setCookie('questionTimer', this.questionTimer);
        }

        const filteredAnswersCookie = this.getCookie('filteredAnswers');
        this.filteredAnswers = filteredAnswersCookie ? JSON.parse(filteredAnswersCookie) : [];

        const waitingResponseCookie = this.getCookie('waiting_response');
        this.waiting_response = waitingResponseCookie ? JSON.parse(waitingResponseCookie) : true;

        const statusanswerCookie = this.getCookie('status_answer');
        this.status_answer = statusanswerCookie ? JSON.parse(statusanswerCookie) : 0;

        if (this.room[0].status_game == 1) {
            this.startTimer()
        }
        const self = this;
        window.Echo.channel('openuser_room').listen('.openuser_room', response => {
            self.game_user.unshift(response.game_user);
            this.$forceUpdate();
        });
        window.Echo.channel('update_room').listen('.update_room', response => {
            console.log(response);
            const roomId = response.room.id;
            const index = this.room.findIndex(item => item.id === roomId);
            if (index !== -1) {
                this.room.splice(index, 1, response.room);
            }
            if (response.room.status_game == 1) {
                this.status_answer = 0;
                this.setCookie('status_answer', this.status_answer);
            }
            this.$forceUpdate();
        });
        window.Echo.channel('update_score').listen('.update_score', response => {
            const userId = (response.game_user.id);
            const index = this.game_user.findIndex(item => item.id === userId);
            if (index !== -1) {
                this.game_user.splice(index, 1, response.game_user);
            }
            // const sesstion_id = response.room.id;
            // const index = this.room.findIndex(item => item.id === roomId);
            // if (index !== -1) {
            //   this.room.splice(index, 1, response.room);
            // }
            this.$forceUpdate();
        });
    },
    beforeUnmount() {
        window.removeEventListener('beforeunload', this.handleBeforeUnload);
    },
    mounted() {
        window.addEventListener('beforeunload', this.handleBeforeUnload);
    },
    methods: {
        progress(event, progress, stepValue) {
            console.log(stepValue);
        },
        progress_end(event) {
            console.log("Circle progress end");
        },
        handleBeforeUnload(event) {
            // Здесь можно добавить дополнительную логику перед закрытием страницы
            event.preventDefault();
            event.returnValue = '';
        },
        handleStatusGameChange() {
            const currentQuestionId = this.questions[this.room[0].question_current].id;
            this.filteredAnswers = this.answers.filter(answer => answer.question_id === currentQuestionId || answer
                .user_id === this.user_id);
            this.$forceUpdate();
        },
        SetUserAnswer(count) {
            const answ = this.answers.find(answer => answer.id === count);
            console.log(answ);
            if (answ.status == "true") {
                this.status_answer = 1;
                this.setCookie('status_answer', this.status_answer);
                this.AddUserCount(this.session);
            } else {
                this.status_answer = -1;
                this.setCookie('status_answer', this.status_answer);
            }
            this.$forceUpdate();
        },
        startTimer() {
            if (this.user_id == this.room[0].room_owner) {
                if (this.questionTimer && this.questionTimer > 0) {
                    this.questionTimer--;
                    setTimeout(this.startTimer, 1000); // Запускайте startTimer() через 1 секунду
                } else if (this.questionTimer === 0) {
                    this.StopGame(this.room[0].id);
                }
            }
        },
        AddUserCount(session_id) {
            axios
                .put(`http://127.0.0.1:8000/updatescore/${session_id}`, {
                    score: 1
                })
                .then(response => {})
                .catch(error => {})
        },
        StartGame(count) {
            axios
                .put(`http://127.0.0.1:8000/room/${count}`, {
                    pin: this.room[0].pin,
                    room_owner: this.room[0].room_owner,
                    status_game: 1,
                })
                .then(response => {
                    console.log(response.data);
                    this.$toast.success('Игра начинается');
                    const index = this.room.findIndex(item => item.id === response.data.id);
                    if (index !== -1) {
                        this.room.splice(index, 1, response.data);
                    }
                    this.questionTimer = this.questions[this.room[0].question_current].timer;
                    const currentQuestionId = this.questions[this.room[0].question_current].id;
                    this.filteredAnswers = this.answers.filter(answer => answer.question_id === currentQuestionId ||
                        answer.user_id === this.user_id);
                    this.status_answer = 0;
                    this.setCookie('status_answer', this.status_answer);
                    this.$forceUpdate();
                    this.startTimer();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        StopGame(count) {
            axios
                .put(`http://127.0.0.1:8000/room/${count}`, {
                    pin: this.room[0].pin,
                    room_owner: this.room[0].room_owner,
                    status_game: 2
                })
                .then(response => {
                    this.$toast.success('Промежуточные результаты');
                    const index = this.room.findIndex(item => item.id === response.data.id);
                    if (index !== -1) {
                        this.room.splice(index, 1, response.data);
                    }
                    this.$forceUpdate();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        GameContinue(count) {
            axios
                .put(`http://127.0.0.1:8000/room/${count}`, {
                    pin: this.room[0].pin,
                    room_owner: this.room[0].room_owner,
                    status_game: 1,
                    question_current: this.room[0].question_current + 1,
                })
                .then(response => {
                    console.log(response.data);
                    this.$toast.success('Игра начинается');
                    const index = this.room.findIndex(item => item.id === response.data.id);
                    if (index !== -1) {
                        this.room.splice(index, 1, response.data);
                    }
                    this.questionTimer = this.questions[this.room[0].question_current].timer;
                    const currentQuestionId = this.questions[this.room[0].question_current].id;
                    this.filteredAnswers = this.answers.filter(answer => answer.question_id === currentQuestionId);
                    this.status_answer = 0;
                    this.setCookie('status_answer', this.status_answer);
                    this.$forceUpdate();
                    this.startTimer();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        GameEnd(count) {
            axios
                .put(`http://127.0.0.1:8000/room/${count}`, {
                    pin: this.room[0].pin,
                    room_owner: this.room[0].room_owner,
                    status_game: 3
                })
                .then(response => {
                    this.$toast.success('Игра закрывается');
                    const index = this.room.findIndex(item => item.id === response.data.id);
                    if (index !== -1) {
                        this.room.splice(index, 1, response.data);
                    }
                    this.$forceUpdate();
                })
                .catch(error => {
                    console.error(error);
                });
        },
        setCookie(name, value, days = 1) {
            const date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            const expires = `expires=${date.toUTCString()}`;
            document.cookie = `${name}=${value};${expires};path=/`;
        },
        getCookie(name) {
            const cookieName = `${name}=`;
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                let cookie = cookies[i];
                while (cookie.charAt(0) === ' ') {
                    cookie = cookie.substring(1);
                }
                if (cookie.indexOf(cookieName) === 0) {
                    return cookie.substring(cookieName.length, cookie.length);
                }
            }
            return '';
        },
    }
};
</script>

<style>
.status_answer_logo {
    width: 150px;
    padding: 10px;
    border-bottom: 1px solid black;
}

.inputs {
    width: 50%;
}

.room_pin {
    text-align: center;
}

label {
    font-size: 25px;
}

.blocks {
    padding-top: 100px;
    display: inline-block;
    color: black;
    text-align: center;
    font-size: 20px;
    height: 250px;
}

.users {
    font-size: 20px;
    padding: 5px;
    margin: 2px;
    background: #def0db;
}

.users_green {
    font-size: 20px;
    padding: 5px;
    margin: 2px;
    background: #c1f7b7;
}

.btne {
    border: 0px;
}

.btn0 {
    width: 50%;
    background-color: rgba(255, 0, 0, 0.847);
    word-break: break-all;
    overflow: hidden;
}

.btn btn0:hover {
    background: rgba(0, 0, 0, 0);
}

.btn1 {
    width: 50%;
    background-color: rgba(255, 255, 0, 0.805);
    word-break: break-all;
    overflow: hidden;
}

.btn2 {

    width: 50%;
    background-color: rgb(0, 255, 0);
    word-break: break-all;
    overflow: hidden;
}

.btn3 {

    width: 50%;
    background-color: rgb(0, 217, 255);
    word-break: break-all;
    overflow: hidden;
}
</style>