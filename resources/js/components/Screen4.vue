<template>
  <div class="bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Screen 4 - Video Conference</h1>
    
    <!-- Countdown Timer -->
    <div class="mb-6 p-4 bg-gray-100 rounded-lg">
      <h2 class="text-xl font-semibold mb-2">Time Remaining</h2>
      <div class="text-3xl font-bold text-blue-600">{{ formatTime(timeRemaining) }}</div>
    </div>

    <!-- Video Controls -->
    <div class="mb-4 flex space-x-4">
      <button 
        @click="toggleCamera" 
        :class="['px-4 py-2 rounded', isCameraOn ? 'bg-red-500' : 'bg-green-500', 'text-white']"
      >
        {{ isCameraOn ? 'Turn Off Camera' : 'Turn On Camera' }}
      </button>
      <button 
        @click="toggleScreenShare" 
        :class="['px-4 py-2 rounded', isScreenSharing ? 'bg-red-500' : 'bg-blue-500', 'text-white']"
      >
        {{ isScreenSharing ? 'Stop Sharing' : 'Share Screen' }}
      </button>
      <button 
        @click="toggleAudio" 
        :class="['px-4 py-2 rounded', isAudioOn ? 'bg-red-500' : 'bg-green-500', 'text-white']"
      >
        {{ isAudioOn ? 'Mute' : 'Unmute' }}
      </button>
      <button 
        @click="toggleRecording" 
        :class="['px-4 py-2 rounded', isRecording ? 'bg-red-500' : 'bg-green-500', 'text-white']"
      >
        {{ isRecording ? 'Stop Recording' : 'Start Recording' }}
      </button>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Left Column: Video and Transcription -->
      <div>
        <!-- Transcription Display -->
        <div v-if="transcription.length > 0" class="mb-4 p-4 bg-gray-50 rounded-lg">
          <h2 class="text-xl font-semibold mb-2">Transcription</h2>
          <div class="max-h-40 overflow-y-auto">
            <p v-for="(line, index) in transcription" :key="index" class="mb-2">{{ line }}</p>
          </div>
        </div>

        <!-- Video Container -->
        <div class="grid grid-cols-1 gap-4">
          <!-- Local Video -->
          <div class="relative">
            <video
              ref="localVideo"
              autoplay
              playsinline
              muted
              class="w-full rounded-lg bg-gray-900"
            ></video>
            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded">
              You
            </div>
          </div>

          <!-- Screen Share / Remote Video -->
          <div class="relative">
            <video
              ref="screenVideo"
              autoplay
              playsinline
              class="w-full rounded-lg bg-gray-900"
            ></video>
            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded">
              Screen Share
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: AI Interviewer Chat -->
      <div class="flex flex-col">
        <!-- Interview Progress -->
        <div class="mb-4 p-4 bg-blue-50 rounded-lg">
          <h2 class="text-xl font-semibold mb-2">Interview Progress</h2>
          <div class="space-y-2">
            <div class="flex justify-between">
              <span>Overall Score:</span>
              <span class="font-bold">{{ interviewScore }}%</span>
            </div>
            <div class="flex justify-between">
              <span>Questions Answered:</span>
              <span class="font-bold">{{ questionsAnswered }}/{{ totalQuestions }}</span>
            </div>
            <div class="flex justify-between">
              <span>Time Elapsed:</span>
              <span class="font-bold">{{ formatTime(3600 - timeRemaining) }}</span>
            </div>
          </div>
        </div>

        <!-- AI Interviewer Chat -->
        <div class="flex-1 bg-gray-50 rounded-lg p-4 flex flex-col">
          <h2 class="text-xl font-semibold mb-4">AI Interviewer</h2>
          
          <!-- Chat Messages -->
          <div class="flex-1 overflow-y-auto mb-4 space-y-4">
            <div v-for="(message, index) in chatMessages" :key="index" 
                 :class="['p-3 rounded-lg max-w-[80%]', 
                         message.type === 'ai' ? 'bg-blue-100 ml-0' : 'bg-gray-100 ml-auto']">
              <div class="font-semibold mb-1">{{ message.type === 'ai' ? 'Interviewer' : 'You' }}</div>
              <div class="whitespace-pre-wrap">{{ message.content }}</div>
            </div>
          </div>

          <!-- Feedback Section -->
          <div v-if="currentFeedback" class="mt-4 p-4 bg-yellow-50 rounded-lg">
            <h3 class="font-semibold mb-2">Real-time Feedback</h3>
            <div class="whitespace-pre-wrap">{{ currentFeedback }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';

// State
const timeRemaining = ref(3600); // 1 hour in seconds
const isCameraOn = ref(false);
const isScreenSharing = ref(false);
const isAudioOn = ref(false);
const isRecording = ref(false);
const transcription = ref([]);
const chatMessages = ref([]);
const currentFeedback = ref('');
const interviewScore = ref(0);
const questionsAnswered = ref(0);
const totalQuestions = ref(0);
const localVideo = ref(null);
const screenVideo = ref(null);
let localStream = null;
let screenStream = null;
let audioContext = null;
let mediaRecorder = null;
let audioChunks = [];
let recognition = null;
let timerInterval = null;
let lastTranscriptionLength = 0;

// Watch for new transcriptions
watch(transcription, (newTranscription) => {
  if (newTranscription.length > lastTranscriptionLength) {
    const newText = newTranscription[newTranscription.length - 1];
    analyzeTranscription(newText);
  }
  lastTranscriptionLength = newTranscription.length;
});

// AI Analysis function
const analyzeTranscription = async (text) => {
  try {
    // Add user message to chat
    chatMessages.value.push({
      type: 'user',
      content: text
    });

    // Call your AI endpoint
    const response = await fetch('/api/analyze-interview', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        transcription: text,
        context: {
          currentScore: interviewScore.value,
          questionsAnswered: questionsAnswered.value,
          totalQuestions: totalQuestions.value,
          timeRemaining: timeRemaining.value
        }
      })
    });

    const data = await response.json();
    
    // Update chat with AI response
    chatMessages.value.push({
      type: 'ai',
      content: data.response
    });

    // Update feedback
    currentFeedback.value = data.feedback;

    // Update metrics
    interviewScore.value = data.score;
    questionsAnswered.value = data.questionsAnswered;
    totalQuestions.value = data.totalQuestions;

  } catch (error) {
    console.error('Error analyzing transcription:', error);
  }
};

// Timer function
const startTimer = () => {
  timerInterval = setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value--;
    } else {
      clearInterval(timerInterval);
      stopAllMedia();
    }
  }, 1000);
};

const formatTime = (seconds) => {
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const remainingSeconds = seconds % 60;
  return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
};

// Speech Recognition Setup
const setupSpeechRecognition = () => {
  if ('webkitSpeechRecognition' in window) {
    recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'en-US';

    recognition.onresult = (event) => {
      const result = event.results[event.results.length - 1];
      const transcript = result[0].transcript;
      
      if (result.isFinal) {
        transcription.value.push(transcript);
      }
    };

    recognition.onerror = (event) => {
      console.error('Speech recognition error:', event.error);
    };
  } else {
    console.error('Speech recognition not supported in this browser');
  }
};

// Recording functions
const toggleRecording = async () => {
  if (!isRecording.value) {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      audioContext = new AudioContext();
      const source = audioContext.createMediaStreamSource(stream);
      const destination = audioContext.createMediaStreamDestination();
      source.connect(destination);
      
      mediaRecorder = new MediaRecorder(destination.stream);
      audioChunks = [];

      mediaRecorder.ondataavailable = (event) => {
        audioChunks.push(event.data);
      };

      mediaRecorder.onstop = () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
        const audioUrl = URL.createObjectURL(audioBlob);
        const a = document.createElement('a');
        a.href = audioUrl;
        a.download = `recording-${new Date().toISOString()}.wav`;
        a.click();
        URL.revokeObjectURL(audioUrl);
      };

      mediaRecorder.start();
      recognition?.start();
      isRecording.value = true;
    } catch (error) {
      console.error('Error starting recording:', error);
    }
  } else {
    stopRecording();
  }
};

const stopRecording = () => {
  if (mediaRecorder && mediaRecorder.state !== 'inactive') {
    mediaRecorder.stop();
    recognition?.stop();
    isRecording.value = false;
  }
};

// Media functions
const toggleCamera = async () => {
  try {
    if (!isCameraOn.value) {
      localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
      localVideo.value.srcObject = localStream;
      isCameraOn.value = true;
    } else {
      stopCamera();
    }
  } catch (error) {
    console.error('Error accessing camera:', error);
  }
};

const toggleScreenShare = async () => {
  try {
    if (!isScreenSharing.value) {
      screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
      screenVideo.value.srcObject = screenStream;
      isScreenSharing.value = true;
    } else {
      stopScreenShare();
    }
  } catch (error) {
    console.error('Error sharing screen:', error);
  }
};

const toggleAudio = async () => {
  try {
    if (!isAudioOn.value) {
      audioContext = new AudioContext();
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      const source = audioContext.createMediaStreamSource(stream);
      const destination = audioContext.createMediaStreamDestination();
      source.connect(destination);
      
      mediaRecorder = new MediaRecorder(destination.stream);
      mediaRecorder.start();
      isAudioOn.value = true;
    } else {
      stopAudio();
    }
  } catch (error) {
    console.error('Error accessing audio:', error);
  }
};

const stopCamera = () => {
  if (localStream) {
    localStream.getTracks().forEach(track => track.stop());
    localVideo.value.srcObject = null;
    localStream = null;
    isCameraOn.value = false;
  }
};

const stopScreenShare = () => {
  if (screenStream) {
    screenStream.getTracks().forEach(track => track.stop());
    screenVideo.value.srcObject = null;
    screenStream = null;
    isScreenSharing.value = false;
  }
};

const stopAudio = () => {
  if (mediaRecorder) {
    mediaRecorder.stop();
    mediaRecorder = null;
    isAudioOn.value = false;
  }
  if (audioContext) {
    audioContext.close();
    audioContext = null;
  }
};

const stopAllMedia = () => {
  stopCamera();
  stopScreenShare();
  stopAudio();
  stopRecording();
};

// Lifecycle hooks
onMounted(() => {
  startTimer();
  setupSpeechRecognition();
  
  // Add initial AI message
  chatMessages.value.push({
    type: 'ai',
    content: "Hello! I'm your AI interviewer today. I'll be analyzing your responses and providing real-time feedback. Please start by introducing yourself."
  });
});

onUnmounted(() => {
  clearInterval(timerInterval);
  stopAllMedia();
});
</script> 