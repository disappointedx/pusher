<template>
    <div>
      <div>Осталось времени: {{ formatTime }}</div>
      <button @click="startTimer" v-if="!isTimerRunning">Старт</button>
      <button @click="stopTimer" v-else>Стоп</button>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        countdown: 20,
        isTimerRunning: false,
        startTime: null
      };
    },
    computed: {
      formatTime() {
        const minutes = Math.floor(this.countdown / 60);
        const seconds = this.countdown % 60;
        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
      }
    },
    mounted() {
      this.loadTimerState();
    },
    methods: {
      startTimer() {
        this.isTimerRunning = true;
        this.startTime = Date.now();
        this.timerInterval = setInterval(() => {
          const elapsedSeconds = Math.floor((Date.now() - this.startTime) / 1000);
          this.countdown = Math.max(0, this.countdown - elapsedSeconds);
          this.startTime = Date.now();
          this.saveTimerState();
          if (this.countdown === 0) {
            this.countdown = 20;
          }
        }, 1000);
      },
      stopTimer() {
        this.isTimerRunning = false;
        clearInterval(this.timerInterval);
        this.startTime = null;
        this.saveTimerState();
      },
      saveTimerState() {
        localStorage.setItem('timerCountdown', this.countdown.toString());
        localStorage.setItem('timerStartTime', this.startTime ? this.startTime.toString() : '');
      },
      loadTimerState() {
        const savedCountdown = localStorage.getItem('timerCountdown');
        const savedStartTime = localStorage.getItem('timerStartTime');
        if (savedCountdown && savedStartTime) {
          this.countdown = parseInt(savedCountdown);
          this.startTime = parseInt(savedStartTime);
          if (this.countdown === 0) {
            this.startTimer();
          }
        }
      }
    }
  };
  </script>
  