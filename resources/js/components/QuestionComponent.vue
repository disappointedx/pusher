<template>
  <div class="container">
    <div class="content">
      <div class="row">
        <div class="col col-2 col-style" v-if="questions">
          <div v-for="(quest, index) in questions" :key="index">
            <button class="btn btn-primary my-1" @click.prevent="SetCount(index)">{{ index + 1 }}</button>
          </div>
          <button class="btn btn-primary" @click.prevent="AddQuestion()">+</button>
        </div>
        <div class="col col-style">
          <div class="Question">
            Введите вопрос
            <br>
            <input type="text" class = "form-control" v-model="question.title" />
            <div class="row my-2">
              <div class="col my-3">Таймер</div>
              <div class="col my-2"><input type="text" class="form-control timer" v-model="question.timer" /></div>
            </div>
          </div>
          <div v-for="(ans, index) in answer" :key="index">
            <input type="text" v-model="ans.title" />
              <input type="checkbox" v-model="ans.status" />
          </div>
          <button class="btn btn-primary my-2" @click.prevent="Create()">Создать</button>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import axios from 'axios';
export default {
  props: ['test', 'questions', 'answers'],
  data() {
    return {
      current: 0,
      answers_number: 4,
      answer: [],
      question: { title: '', timer: '' }
    };
  },
  async mounted() {
    await this.transfertodata();
  },
  methods: {
    async Create() {
      await this.CreateQuestion();
      await this.CreateAnswer(); // Добавляем await для ожидания создания ответов
      location.reload();
      this.transfertodata();
    },
    async transfertodata() {
      this.question = {
        title: this.questions[this.current]?.title || '',
        timer: this.questions[this.current]?.timer || ''
      };
      const currentQuestionId = this.questions[this.current]?.id;
      this.answer = this.answers.filter(answer => answer.question_id === currentQuestionId) || [];
      if (this.answer.length === 0) {
        for (let i = 0; i < this.answers_number; i++) {
          this.answer.push({ title: '', status: 'false' });
        }
      }
    },
    async CreateQuestion() {
      const questionData = {
        title: this.question.title,
        test_id: this.test.id,
        timer: this.question.timer
      };

      try {
        const response = await axios.post('http://10.3.3.18/question', questionData);
        const questionId = response.data.id; // Получаем идентификатор созданного вопроса
        this.CreateAnswer(questionId); // Передаем идентификатор в метод создания ответов
        this.$toast.success('Вопрос и ответы добавлены');
      } catch (error) {
        this.$toast.error('Не удалось создать вопрос и ответы');
        console.error(error);
      }
    },
    CreateAnswer(questionId) {
      for (let i = 0; i < this.answers_number; i++) {
        const answerData = {
          title: this.answer[i].title,
          status: this.answer[i].status.toString(),
          count: i,
          question_id: questionId, // Используем полученный идентификатор вопроса
          test_id: this.test.id
        };

        axios
          .post(`http://10.3.3.18/answer`, answerData)
          .then((response) => {
            this.$toast.success('Ответ добавлен');
            console.log(response);
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
    AddQuestion() {
      const quest = {
        title: "",
        timer: ""
      };
      this.questions.push(quest);

      const emptyAnswers = [];
      for (let i = 0; i < this.answers_number; i++) {
        emptyAnswers.push({ title: '', status: 'false' });
      }
      this.answer.push(emptyAnswers);

      this.transfertodata();
    },
    SetCount(count) {
      this.current = count;
      this.transfertodata();
    },
    UpdateQuestion() {
      // Ваш код для обновления вопроса
    },
    TransferQuestion() {
      // Ваш код для передачи вопроса
    }
  },
};
</script>


<style scoped>
.btn {
  width: auto;
}
.col-style {
  text-align: center;
  overflow: hidden;
  background-color: #ffffff;
  padding: 20px 10px;
  box-shadow: 0 0 8px rgba(60, 59, 59, 0.5);
  font-size: 18px;
}
.timer {
  width: 50px;
}
.toggle-pill-dark {
  display: flex;
  align-items: center;
}
.toggle-pill-dark input[type="checkbox"] {
  display: none;
}
.toggle-pill-dark input[type="checkbox"] + label {
  display: block;
  position: relative;
  width: 3em;
  height: 1.6em;
  margin-bottom: 20px;
  border-radius: 1em;
  background: #303e58;
  box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.3);
  cursor: pointer;

  -webkit-transition: background 0.1s ease-in-out;
  transition: background 0.1s ease-in-out;
}
.toggle-pill-dark input[type="checkbox"] + label:before {
  content: "";
  display: block;
  width: 1.2em;
  height: 1.2em;
  border-radius: 1em;
  background: #e84d4d;
  position: absolute;
  left: 0.2em;
  top: 0.2em;
  -webkit-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
  -webkit-transform: rotate(90deg);
  transform: rotate(90deg);
}
.toggle-pill-dark input[type="checkbox"]:checked + label:before {
  background: #47cf73;
  box-shadow: -2px 0px 5px rgba(0, 0, 0, 0.2);
  left: 1.6em;
  -webkit-transform: rotate(295deg);
  transform: rotate(295deg);
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.content {
  width: 500px;
}

.row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.Question {
  margin-bottom: 20px;
}
</style>
