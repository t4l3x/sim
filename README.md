## Scoring Rules
This table is used to define the point system used in a league. For instance, the points awarded for a win, draw, or loss can be specified here. If different leagues use different scoring systems, each league would have its own set of scoring rules.

## Leagues
This table holds data for each league. Each league is linked to a set of scoring rules through the `scoring_rule_id` field.

## Teams
The teams table stores basic information about each team, such as the team's name.

## League Teams
This table creates a relationship between leagues and teams, indicating which teams are participating in which leagues. The `strength_percent` field could be used to represent the overall strength of a team in a given league.

## Attributes
This is a flexible table that can hold any additional attributes related to an entity, such as a league, team, or match. The type of entity and its ID are specified, along with the name and value of the attribute. For example, you could use this table to store the attack, midfield, and defense ratings of a team in a league, or to store the weather conditions during a match.

## Matches
This table is similar to the fixtures table in the previous schema. It represents matches that are scheduled or have been played in a league. It includes the teams that are playing, the number of goals scored by each team, and when the match was played.

## Statistics
This table holds statistics for teams in a league. Each record is linked to a team in a league (through the `league_teams_id` field) and specifies the name and value of the statistic. This could be used to store statistics such as the number of matches played, won, lost, or drawn, the total number of goals scored, and so on.

