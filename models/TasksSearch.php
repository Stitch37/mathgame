<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tasks;

/**
 * TasksSearch represents the model behind the search form of `app\models\Tasks`.
 */
class TasksSearch extends Tasks
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'reward', 'level', 'difficulty', 'hint_cost', 'number'], 'integer'],
            [['text', 'name', 'answer', 'hint', 'answer_type'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Tasks::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'reward' => $this->reward,
            'level' => $this->level,
            'difficulty' => $this->difficulty,
            'hint_cost' => $this->hint_cost,
            'number' => $this->number,
        ]);

        $query->andFilterWhere(['ilike', 'text', $this->text])
            ->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'answer', $this->answer])
            ->andFilterWhere(['ilike', 'hint', $this->hint])
            ->andFilterWhere(['ilike', 'answer_type', $this->answer_type]);

        return $dataProvider;
    }
}
